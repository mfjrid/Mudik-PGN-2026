<?php

namespace App\Services;

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    public function __construct()
    {
        Configuration::setApiKey(config('services.xendit.api_key'));
    }

    public function createInvoice($registration)
    {
        $apiInstance = new InvoiceApi();
        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => 'REG-' . $registration->id . '-' . time(),
            'amount' => $registration->deposit_amount,
            'description' => 'Deposit Pendaftaran Mudik PGN 2026 - registration #' . $registration->id,
            'customer' => [
                'given_names' => $registration->user->name,
                'email' => $registration->user->email,
            ],
            'success_redirect_url' => route('passenger.registration.success', $registration),
        ]);

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Xendit Error: ' . $e->getMessage());
        }
    }
}
