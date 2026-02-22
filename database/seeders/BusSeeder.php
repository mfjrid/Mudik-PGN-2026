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
            [
                'bus_number' => 'PGN-01', 
                'route_name' => 'Jakarta - Semarang - Surabaya', 
                'capacity' => 44, 
                'layout_type' => '2-2'
            ],
            [
                'bus_number' => 'PGN-02', 
                'route_name' => 'Jakarta - Yogyakarta - Solo', 
                'capacity' => 33, 
                'layout_type' => '2-1'
            ],
            [
                'bus_number' => 'PGN-03', 
                'route_name' => 'Jakarta - Purwokerto - Wonosobo', 
                'capacity' => 44, 
                'layout_type' => '2-2'
            ],
        ];

        foreach ($busData as $data) {
            $bus = Bus::updateOrCreate(
                ['bus_number' => $data['bus_number']],
                [
                    'route_name' => $data['route_name'],
                    'capacity' => $data['capacity'],
                    'layout_type' => $data['layout_type'],
                ]
            );
            
            // Only create seats if they don't exist
            if ($bus->wasRecentlyCreated || $bus->seats()->count() === 0) {
                $this->generateSeats($bus);
            }
        }
    }

    private function generateSeats(Bus $bus)
    {
        $capacity = $bus->capacity;
        $layout = $bus->layout_type; // '2-2' or '2-1'
        
        $seatCounter = 1;
        $row = 1;

        if ($layout === '2-2') {
            // Standard Bus: 4 seats per row (2 left, 2 right)
            while ($seatCounter <= $capacity) {
                for ($col = 1; $col <= 5; $col++) {
                    if ($col === 3) continue; // Aisle (column 3 is empty)
                    if ($seatCounter > $capacity) break;

                    Seat::create([
                        'bus_id' => $bus->id,
                        'seat_number' => $seatCounter++,
                        'row' => $row,
                        'column' => $col,
                        'status' => 'available',
                    ]);
                }
                $row++;
            }
        } elseif ($layout === '2-1') {
            // Executive Bus: 3 seats per row (2 left, 1 right)
            while ($seatCounter <= $capacity) {
                for ($col = 1; $col <= 4; $col++) {
                    if ($col === 3) continue; // Aisle (column 3 is empty)
                    if ($seatCounter > $capacity) break;

                    Seat::create([
                        'bus_id' => $bus->id,
                        'seat_number' => $seatCounter++,
                        'row' => $row,
                        'column' => $col,
                        'status' => 'available',
                    ]);
                }
                $row++;
            }
        }
    }
}
