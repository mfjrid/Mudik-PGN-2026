<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        $callbackToken = $request->header('x-callback-token');
        
        if ($callbackToken !== config('services.xendit.callback_token')) {
            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        $data = $request->all();
        Log::info('Xendit Webhook Received', $data);

        if ($data['status'] === 'PAID') {
            // Extract registration ID from external_id (format: REG-{id}-{time})
            $externalIdParts = explode('-', $data['external_id']);
            if (count($externalIdParts) < 2) return response()->json(['message' => 'Invalid external ID'], 400);
            
            $registrationId = $externalIdParts[1];
            $registration = Registration::find($registrationId);

            if ($registration) {
                DB::beginTransaction();
                try {
                    $registration->update([
                        'payment_status' => 'paid',
                        'status' => 'accepted' // Automatically accept after payment? Or wait for admin?
                    ]);

                    Payment::create([
                        'registration_id' => $registration->id,
                        'external_id' => $data['id'],
                        'amount' => $data['amount'],
                        'type' => 'deposit',
                        'status' => 'completed',
                        'payment_method' => $data['payment_method'] ?? 'unknown',
                    ]);

                    DB::commit();
                    return response()->json(['message' => 'Payment processed successfully']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Webhook Processing Error: ' . $e->getMessage());
                    return response()->json(['message' => 'Error processing payment'], 500);
                }
            }
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
