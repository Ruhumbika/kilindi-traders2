<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TradersImport;
use App\Exports\TradersTemplateExport;
use App\Models\Trader;

class ImportController extends Controller
{
    /**
     * Show the import form
     */
    public function index()
    {
        return view('imports.index');
    }

    /**
     * Import traders from Excel file
     */
    public function importTraders(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:51200', // Increased to 50MB for large imports
        ]);

        try {
            // Ensure imports directory exists
            $importsDir = storage_path('app' . DIRECTORY_SEPARATOR . 'imports');
            if (!is_dir($importsDir)) {
                mkdir($importsDir, 0755, true);
            }

            $file = $request->file('file');
            
            // Generate unique filename
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $importsDir . DIRECTORY_SEPARATOR . $filename;
            
            // Move file directly to the imports directory
            $file->move($importsDir, $filename);

            // Debug: Check if file exists and log paths
            \Log::info('Import Debug', [
                'filename' => $filename,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath),
                'storage_path' => storage_path('app'),
                'directory_exists' => is_dir(dirname($fullPath))
            ]);

            // Ensure the file exists before processing
            if (!file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File upload failed. File not found at: ' . $fullPath,
                    'debug' => [
                        'path' => $path,
                        'full_path' => $fullPath,
                        'storage_path' => storage_path('app'),
                        'imports_dir_exists' => is_dir(storage_path('app/imports'))
                    ]
                ], 500);
            }

            // Set longer execution time for the request
            set_time_limit(600); // 10 minutes

            $import = new TradersImport();
            $results = $import->import($fullPath);

            // Clean up the uploaded file safely
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $message = "Import completed! Successfully imported {$results['success']} traders.";
            if ($results['errors'] > 0) {
                $message .= " Errors: {$results['errors']}.";
            }
            if ($results['skipped'] > 0) {
                $message .= " Skipped: {$results['skipped']}.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results,
                'summary' => [
                    'total_processed' => $results['success'] + $results['errors'] + $results['skipped'],
                    'successful' => $results['success'],
                    'failed' => $results['errors'],
                    'skipped' => $results['skipped']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Download sample Excel template for traders
     */
    public function downloadTradersTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="traders_import_template.xlsx"',
        ];

        $templateExport = new TradersTemplateExport();
        $data = $templateExport->getData();

        // Create a simple CSV response since Excel interfaces are not working
        $csvContent = '';
        foreach ($data as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="traders_import_template.csv"',
        ]);
    }
}
