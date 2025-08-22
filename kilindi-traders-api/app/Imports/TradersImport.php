<?php

namespace App\Imports;

use App\Models\Trader;
use App\Events\TraderRegistered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TradersImport
{
    private $batchSize = 100; // Process in batches of 100
    
    public function import($filePath)
    {
        // Increase execution time and memory for large imports
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');
        
        $results = [
            'success' => 0,
            'errors' => 0,
            'skipped' => 0,
            'messages' => []
        ];

        // Normalize file path for Windows compatibility
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
        
        if (!file_exists($filePath)) {
            $results['messages'][] = 'File not found: ' . $filePath;
            return $results;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $results['messages'][] = 'Could not open file for reading';
            return $results;
        }

        // Skip header row
        $header = fgetcsv($handle);
        
        $batch = [];
        $rowNumber = 1;
        
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            
            if (count($row) < 7) {
                $results['skipped']++;
                $results['messages'][] = "Row {$rowNumber}: Insufficient columns";
                continue;
            }

            $data = [
                'owner_name' => trim($row[0]),
                'phone_number' => trim($row[1]),
                'email' => trim($row[2]) ?: null,
                'business_name' => trim($row[3]),
                'business_type' => trim($row[4]),
                'business_location' => trim($row[5]),
                'control_number' => trim($row[6]) ?: null,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Validate the data
            $validator = Validator::make($data, [
                'owner_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'business_name' => 'required|string|max:255',
                'business_type' => 'required|string|max:100',
                'business_location' => 'required|string|max:255',
                'control_number' => 'nullable|string|max:50|unique:traders,control_number'
            ]);

            if ($validator->fails()) {
                $results['errors']++;
                $results['messages'][] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            $batch[] = $data;
            
            // Process batch when it reaches the batch size
            if (count($batch) >= $this->batchSize) {
                $batchResults = $this->processBatch($batch, $rowNumber - count($batch) + 1);
                $results['success'] += $batchResults['success'];
                $results['errors'] += $batchResults['errors'];
                $results['messages'] = array_merge($results['messages'], $batchResults['messages']);
                
                $batch = []; // Reset batch
                
                // Clear memory and allow other processes
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }
        }
        
        // Process remaining items in the last batch
        if (!empty($batch)) {
            $batchResults = $this->processBatch($batch, $rowNumber - count($batch) + 1);
            $results['success'] += $batchResults['success'];
            $results['errors'] += $batchResults['errors'];
            $results['messages'] = array_merge($results['messages'], $batchResults['messages']);
        }

        fclose($handle);
        return $results;
    }
    
    private function processBatch($batch, $startRowNumber)
    {
        $results = [
            'success' => 0,
            'errors' => 0,
            'messages' => []
        ];
        
        try {
            DB::beginTransaction();
            
            // Bulk insert for better performance
            $insertData = [];
            foreach ($batch as $data) {
                $insertData[] = $data;
            }
            
            // Insert all records at once
            DB::table('traders')->insert($insertData);
            
            // Get the inserted traders for events (less efficient but necessary for events)
            $insertedTraders = Trader::where('created_at', '>=', now()->subMinute())
                                   ->orderBy('id', 'desc')
                                   ->take(count($batch))
                                   ->get();
            
            // Fire events for each trader (in chunks to avoid memory issues)
            foreach ($insertedTraders->chunk(50) as $traderChunk) {
                foreach ($traderChunk as $trader) {
                    try {
                        event(new TraderRegistered($trader));
                    } catch (\Exception $e) {
                        Log::warning('Event firing failed for trader', ['trader_id' => $trader->id, 'error' => $e->getMessage()]);
                    }
                }
            }
            
            DB::commit();
            $results['success'] = count($batch);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $results['errors'] = count($batch);
            $results['messages'][] = "Batch starting at row {$startRowNumber}: " . $e->getMessage();
            Log::error('Batch import error', ['start_row' => $startRowNumber, 'batch_size' => count($batch), 'error' => $e->getMessage()]);
        }
        
        return $results;
    }
}
