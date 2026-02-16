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
        ]);

        $bus = Bus::create($request->all());

        // Automatically create seats for the bus
        for ($i = 1; $i <= $request->capacity; $i++) {
            Seat::create([
                'bus_id' => $bus->id,
                'seat_number' => $i,
                'status' => 'available',
            ]);
        }

        return redirect()->route('admin.buses.index')->with('success', 'Bus and seats created successfully.');
    }

    public function show(Bus $bus)
    {
        $bus->load('seats');
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
        ]);

        $bus->update($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus deleted successfully.');
    }
}
