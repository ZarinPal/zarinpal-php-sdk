<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class UnverifiedResponse
{
    use Fillable;

    public int $code;
    public string $message;
    public array $authorities = [];

    public function __construct(array $data = null)
    {
        if ($data === null || !isset($data['code'], $data['message'])) {
            $this->code = 0;
            $this->message = 'No data received';
            return;
        }

        $this->code = $data['code'];
        $this->message = $data['message'];

        if (isset($data['authorities']) && is_array($data['authorities'])) {
            foreach ($data['authorities'] as $authorityData) {
                $this->authorities[] = [
                    'authority'    => $authorityData['authority'] ?? '',
                    'amount'       => $authorityData['amount'] ?? 0,
                    'callback_url' => $authorityData['callback_url'] ?? '',
                    'referer'      => $authorityData['referer'] ?? '',
                    'date'         => $authorityData['date'] ?? '',
                ];
            }
        }
    }
}
