<?php

namespace Database\Seeders;

use App\Models\Copy;
use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CopySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = Resource::with('authors')->get();

        foreach ($resources as $resource) {
            // Create 2-5 copies for each resource
            $copyCount = rand(2, 5);

            for ($i = 1; $i <= $copyCount; $i++) {
                $copyNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
                $barcode = $this->generateBarcode($resource->id, $i);

                Copy::firstOrCreate(
                    ['barcode' => $barcode],
                    [
                        'resource_id' => $resource->id,
                        'copy_number' => $copyNumber,
                        'barcode' => $barcode,
                        'qr_code' => "QR-{$barcode}",
                        'status' => $this->getRandomStatus(),
                        'location' => $this->getRandomLocation(),
                        'condition' => $this->getRandomCondition(),
                        'purchased_date' => now()->subDays(rand(30, 500)),
                        'purchase_price' => rand(1500, 5000) / 100, // $15.00 - $50.00
                    ]
                );
            }
        }
    }

    /**
     * Generate a unique barcode.
     */
    private function generateBarcode($resourceId, $copyNumber): string
    {
        return sprintf('LIB-%d-%03d-%04d', $resourceId, $copyNumber, rand(1000, 9999));
    }

    /**
     * Get a random status.
     */
    private function getRandomStatus(): string
    {
        $statuses = ['available', 'available', 'available', 'borrowed', 'reserved', 'maintenance'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * Get a random location.
     */
    private function getRandomLocation(): string
    {
        $locations = [
            'A-1-1', 'A-1-2', 'A-2-1', 'A-2-2',
            'B-1-1', 'B-1-2', 'B-2-1', 'B-2-2',
            'C-1-1', 'C-1-2', 'C-2-1', 'C-2-2',
            'D-1-1', 'D-1-2', 'D-2-1', 'D-2-2',
        ];
        return $locations[array_rand($locations)];
    }

    /**
     * Get a random condition.
     */
    private function getRandomCondition(): string
    {
        $conditions = ['new', 'good', 'good', 'good', 'fair', 'poor'];
        return $conditions[array_rand($conditions)];
    }
}
