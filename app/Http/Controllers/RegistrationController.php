<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Registration;
use App\Models\FamilyMember;
use App\Models\Seat;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    private function hasActiveRegistration()
    {
        return Registration::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
    public function create()
    {
        if ($this->hasActiveRegistration()) {
            return redirect()->route('passenger.registration.dashboard')->with('error', 'Anda sudah memiliki pendaftaran yang aktif.');
        }

        $buses = Bus::all();
        return view('passenger.registration.register', compact('buses'));
    }

    public function getSeats(Bus $bus)
    {
        $seats = $bus->seats()->orderBy('id')->get();
        return response()->json([
            'seats' => $seats
        ]);
    }

    public function store(Request $request)
    {
        if ($this->hasActiveRegistration()) {
            return redirect()->route('passenger.registration.dashboard');
        }

        $request->validate([
            'total_members' => 'required|integer|min:1|max:4',
            'departure_location' => 'required',
            'bus_id' => 'required|exists:buses,id',
            'selected_seats' => 'required|array',
            'family' => 'required|array',
        ]);

        if (count($request->selected_seats) != $request->total_members) {
            return back()->withInput()->withErrors(['error' => 'Jumlah kursi yang dipilih harus sama dengan jumlah peserta (' . $request->total_members . ' orang).']);
        }

        try {
            DB::beginTransaction();

            $busId = $request->bus_id;
            $totalMembers = $request->total_members;

            // 1. Lock seats and verify availability
            $seats = Seat::whereIn('id', $request->selected_seats)
                        ->where('bus_id', $busId)
                        ->lockForUpdate()
                        ->get();

            if ($seats->count() !== count($request->selected_seats) || $seats->contains('status', '!=', 'available')) {
                throw new \Exception('Beberapa kursi sudah tidak tersedia. Silakan pilih kembali.');
            }

            if (count($request->selected_seats) != $totalMembers) {
                throw new \Exception('Jumlah kursi yang dipilih harus sama dengan jumlah peserta.');
            }

            // 2. Create Registration
            $registration = Registration::create([
                'user_id' => Auth::id(),
                'bus_id' => $busId,
                'total_members' => $totalMembers,
                'departure_location' => $request->departure_location,
                'status' => 'pending',
                'deposit_amount' => 50000 * $totalMembers, 
            ]);

            // 3. Update Seats and Create Family Members
            foreach ($request->selected_seats as $index => $seatId) {
                $seat = Seat::find($seatId);
                $seat->update(['status' => 'reserved']);

                FamilyMember::create([
                    'registration_id' => $registration->id,
                    'seat_id' => $seatId,
                    'name' => $request->family[$index]['name'],
                    'identity_number' => $request->family[$index]['identity_number'],
                    'age' => $request->family[$index]['age'],
                    'gender' => 'male', 
                    'is_child' => $request->family[$index]['age'] < 12,
                ]);
            }

            DB::commit();
            return redirect()->route('passenger.registration.payment', $registration);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function payment(Registration $registration, XenditService $xendit)
    {
        $registration->load(['bus', 'user']);

        // 1. If already paid, just show the view (it will show 'Paid' status)
        if ($registration->payment_status === 'paid') {
            return view('passenger.registration.success', compact('registration'));
        }
        
        // 2. Otherwise try to get/create invoice
        try {
            $invoice = $xendit->createInvoice($registration);
            $payment_url = $invoice->getInvoiceUrl();
        } catch (\Exception $e) {
            $payment_url = '#'; // Fallback or handle error
            session()->flash('error', 'Gagal membuat tagihan pembayaran: ' . $e->getMessage());
        }

        return view('passenger.registration.success', compact('registration', 'payment_url'));
    }

    public function dashboard()
    {
        $registrations = Registration::with(['bus', 'familyMembers.seat'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('passenger.dashboard', compact('registrations'));
    }

    public function cancel(Request $request, Registration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        if ($registration->status === 'cancelled') {
            return back()->with('error', 'Pendaftaran sudah dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // 1. Update status
            $registration->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->reason ?? 'Dibatalkan oleh penumpang',
            ]);

            // 2. Release seats
            $seatIds = $registration->familyMembers->pluck('seat_id');
            Seat::whereIn('id', $seatIds)->update(['status' => 'available']);

            DB::commit();
            return redirect()->route('passenger.registration.create')->with('success', 'Pendaftaran berhasil dibatalkan dan kursi dilepaskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pendaftaran.');
        }
    }
}
