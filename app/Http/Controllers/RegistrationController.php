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
    public function step1()
    {
        if ($this->hasActiveRegistration()) {
            return redirect()->route('passenger.registration.dashboard')->with('error', 'Anda sudah memiliki pendaftaran yang aktif.');
        }
        return view('passenger.registration.step1');
    }

    public function postStep1(Request $request)
    {
        if ($this->hasActiveRegistration()) return redirect()->route('passenger.registration.dashboard');
        
        // Validation for KK owner and number of members
        $request->validate([
            'total_members' => 'required|integer|min:1|max:4',
            'departure_location' => 'required',
        ]);

        session(['registration_data' => $request->only(['total_members', 'departure_location'])]);

        return redirect()->route('passenger.registration.step2');
    }

    public function step2()
    {
        if ($this->hasActiveRegistration()) return redirect()->route('passenger.registration.dashboard');
        
        $buses = Bus::all();
        return view('passenger.registration.step2', compact('buses'));
    }

    public function postStep2(Request $request)
    {
        if ($this->hasActiveRegistration()) return redirect()->route('passenger.registration.dashboard');

        $request->validate([
            'bus_id' => 'required|exists:buses,id',
        ]);

        $data = session('registration_data', []);
        $data['bus_id'] = $request->bus_id;
        session(['registration_data' => $data]);

        return redirect()->route('passenger.registration.step3');
    }

    public function step3()
    {
        if ($this->hasActiveRegistration()) return redirect()->route('passenger.registration.dashboard');
        
        $data = session('registration_data');
        if (!$data || !isset($data['bus_id'])) return redirect()->route('passenger.registration.step1');
        
        $bus = Bus::with('seats')->findOrFail($data['bus_id']);
        return view('passenger.registration.step3', compact('bus', 'data'));
    }

    public function postStep3(Request $request)
    {
        if ($this->hasActiveRegistration()) return redirect()->route('passenger.registration.dashboard');

        $request->validate([
            'selected_seats' => 'required|array',
            'family' => 'required|array',
        ]);

        $data = session('registration_data');
        
        try {
            DB::beginTransaction();

            // 1. Lock seats and verify availability to prevent race condition
            $seats = Seat::whereIn('id', $request->selected_seats)
                        ->where('bus_id', $data['bus_id'])
                        ->lockForUpdate()
                        ->get();

            if ($seats->count() !== count($request->selected_seats) || $seats->contains('status', '!=', 'available')) {
                throw new \Exception('Beberapa kursi sudah tidak tersedia. Silakan pilih kembali.');
            }

            // 2. Create Registration
            $registration = Registration::create([
                'user_id' => Auth::id(),
                'bus_id' => $data['bus_id'],
                'total_members' => $data['total_members'],
                'departure_location' => $data['departure_location'],
                'status' => 'pending',
                'deposit_amount' => 50000 * $data['total_members'], // Example deposit
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
                    'gender' => 'male', // Default for now
                    'is_child' => $request->family[$index]['age'] < 12,
                ]);
            }

            DB::commit();
            session()->forget('registration_data');
            
            return redirect()->route('passenger.registration.success', $registration);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function success(Registration $registration, XenditService $xendit)
    {
        $registration->load(['bus', 'user']);

        // 1. If already paid, redirect to dashboard
        if ($registration->payment_status === 'paid') {
            return redirect()->route('passenger.registration.dashboard');
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
            return redirect()->route('passenger.registration.step1')->with('success', 'Pendaftaran berhasil dibatalkan dan kursi dilepaskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pendaftaran.');
        }
    }
}
