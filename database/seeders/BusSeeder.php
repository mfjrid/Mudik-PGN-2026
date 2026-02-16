<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Seat;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $busData = [
            ['bus_number' => 'PGN-01', 'route_name' => 'Jakarta - Semarang - Surabaya', 'capacity' => 40],
            ['bus_number' => 'PGN-02', 'route_name' => 'Jakarta - Yogyakarta - Solo', 'capacity' => 40],
            ['bus_number' => 'PGN-03', 'route_name' => 'Jakarta - Purwokerto - Wonosobo', 'capacity' => 40],
        ];

        foreach ($busData as $data) {
            $bus = Bus::updateOrCreate(
                ['bus_number' => $data['bus_number']],
                [
                    'route_name' => $data['route_name'],
                    'capacity' => $data['capacity'],
                ]
            );
            
            // Only create seats if they don't exist
            if ($bus->wasRecentlyCreated || $bus->seats()->count() === 0) {
                for ($i = 1; $i <= $bus->capacity; $i++) {
                    Seat::updateOrCreate([
                        'bus_id' => $bus->id,
                        'seat_number' => $i,
                    ], [
                        'status' => 'available',
                    ]);
                }
            }
        }
    }
}
