<?php

namespace App\Services;

use GuzzleHttp\Client;

class VerificationService
{
    private $whatsappToken;
    private $phoneNumberId;

    public function __construct()
    {
        $this->whatsappToken = 'YOUR_WHATSAPP_TOKEN';
        $this->phoneNumberId = 'YOUR_PHONE_NUMBER_ID';
    }

    public function sendWhatsAppVerification($phone, $code)
    {
        $client = new Client();
        $url = "https://graph.facebook.com/v17.0/{$this->phoneNumberId}/messages";

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->whatsappToken}",
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'template',
                    'template' => [
                        'name' => 'verification_code',
                        'language' => [
                            'code' => 'fr'
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $code]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
