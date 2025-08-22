<?php

namespace App\Exports;

class TradersTemplateExport
{
    public function getData()
    {
        $data = [
            // Headers
            ['Owner Name', 'Phone Number', 'Email', 'Business Name', 'Business Type', 'Business Location', 'Control Number']
        ];

        // Generate 100 sample traders
        $firstNames = ['John', 'Mary', 'Peter', 'Sarah', 'David', 'Grace', 'Michael', 'Anna', 'James', 'Lucy', 'Robert', 'Jane', 'William', 'Elizabeth', 'Joseph', 'Margaret', 'Thomas', 'Susan', 'Charles', 'Jessica'];
        $lastNames = ['Mwangi', 'Njoroge', 'Kamau', 'Wanjiku', 'Otieno', 'Achieng', 'Maina', 'Wanjiru', 'Kiprotich', 'Chebet', 'Mbugua', 'Nyong\'o', 'Kariuki', 'Wambui', 'Ochieng', 'Akinyi', 'Mutua', 'Wanjala', 'Kiplagat', 'Jepkorir'];
        $businessTypes = ['Electronics', 'Clothing', 'Hardware', 'Grocery', 'Restaurant', 'Pharmacy', 'Stationery', 'Furniture', 'Automotive', 'Beauty Salon', 'Tailoring', 'Bakery', 'Mobile Money', 'Cyber Cafe', 'Boutique'];
        $locations = ['Kilindi Town', 'Kilindi Market', 'Kilindi Center', 'Msange', 'Kwediboma', 'Magoma', 'Kilindi Junction', 'Msange Market', 'Kwediboma Center', 'Magoma Village'];

        for ($i = 1; $i <= 1000; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $businessType = $businessTypes[array_rand($businessTypes)];
            $location = $locations[array_rand($locations)];
            
            $ownerName = $firstName . ' ' . $lastName;
            $phoneNumber = '+25571' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
            $email = rand(0, 1) ? strtolower($firstName . '.' . $lastName . '@example.com') : '';
            $businessName = $firstName . ' ' . $businessType;
            
            $controlNumber = 'KLD' . str_pad($i, 6, '0', STR_PAD_LEFT);
            
            $data[] = [
                $ownerName,
                $phoneNumber,
                $email,
                $businessName,
                $businessType,
                $location,
                $controlNumber
            ];
        }

        return $data;
    }
}
