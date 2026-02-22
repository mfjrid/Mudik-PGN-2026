<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Seat;
use App\Models\Registration;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function registrations()
    {
        $registrations = Registration::with(['user', 'bus'])->latest()->get();
        return view('admin.registrations.index', compact('registrations'));
    }

    public function registrationShow(Registration $registration)
    {
        $registration->load(['user', 'bus', 'familyMembers.seat']);
        return view('admin.registrations.show', compact('registration'));
    }

    public function registrationVerify(Request $request, Registration $registration)
    {
        $registration->update(['status' => 'accepted']);
        // Here we could trigger QR generation or etiket upload reminder
        return back()->with('success', 'Pendaftaran telah diverifikasi.');
    }
    public function index()
    {
        $buses = Bus::withCount('seats')->get();
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_number' => 'required|unique:buses',
            'route_name' => 'required',
            'capacity' => 'required|integer|min:1',
            'layout_type' => 'required|in:2-2,2-1',
        ]);

        $bus = Bus::create($request->all());

        $this->regenerateSeats($bus);

        return redirect()->route('admin.buses.index')->with('success', 'Bus and seats created successfully with ' . $bus->layout_type . ' layout.');
    }

    public function show(Bus $bus)
    {
        $bus->load(['seats' => function($q) {
            $q->orderBy('row')->orderBy('column');
        }]);
        return view('admin.buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'bus_number' => 'required|unique:buses,bus_number,' . $bus->id,
            'route_name' => 'required',
            'capacity' => 'required|integer|min:1',
            'layout_type' => 'required|in:2-2,2-1',
        ]);

        $oldCapacity = $bus->capacity;
        $oldLayout = $bus->layout_type;

        $bus->update($request->all());

        // Regenerate seats if layout or capacity changed
        if ($oldCapacity != $bus->capacity || $oldLayout != $bus->layout_type) {
            // Check if there are already registrations for this bus
            if ($bus->registrations()->where('status', '!=', 'cancelled')->exists()) {
                return redirect()->route('admin.buses.index')->with('warning', 'Bus updated, but seats were NOT regenerated because there are active registrations. Please manage seats manually or clear registrations first.');
            }
            $this->regenerateSeats($bus);
            return redirect()->route('admin.buses.index')->with('success', 'Bus updated and seats regenerated successfully.');
        }

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully.');
    }

    private function regenerateSeats(Bus $bus)
    {
        // Delete existing seats if any
        $bus->seats()->delete();

        $capacity = $bus->capacity;
        $layout = $bus->layout_type; // '2-2' or '2-1'
        
        $seatCounter = 1;
        $row = 1;

        if ($layout === '2-2') {
            while ($seatCounter <= $capacity) {
                for ($col = 1; $col <= 5; $col++) {
                    if ($col === 3) continue; // Aisle
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
            while ($seatCounter <= $capacity) {
                for ($col = 1; $col <= 4; $col++) {
                    if ($col === 3) continue; // Aisle
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

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus deleted successfully.');
    }
}
