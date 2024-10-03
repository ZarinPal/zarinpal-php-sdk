<?php

namespace ZarinPal\Sdk;

use InvalidArgumentException;

final class Validator
{
    public static function validateMerchantId(?string $merchantId): void
    {
        if (
            $merchantId === null ||
            !preg_match(
                '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
                $merchantId
            )
        ) {
            throw new InvalidArgumentException('Invalid merchant_id format. It should be a valid UUID.');
        }
    }

    public static function validateAuthority(string $authority): void
    {
        if (!preg_match('/^[AS][0-9a-zA-Z]{35}$/', $authority)) {
            throw new InvalidArgumentException('Invalid authority format. It should be a string starting with "A" or "S" followed by 35 alphanumeric characters.');
        }
    }

    public static function validateAmount(int $amount, int $minAmount = 1): void
    {
        if ($amount < $minAmount) {
            throw new InvalidArgumentException("Amount must be at least {$minAmount}.");
        }
    }

    public static function validateCallbackUrl(string $callback_url): void
    {
        if (!preg_match('/^https?:\/\/.*/', $callback_url)) {
            throw new InvalidArgumentException('Invalid callback URL format. It should start with http:// or https://.');
        }
    }

    public static function validateMobile(?string $mobile): void
    {
        if ($mobile !== null && !preg_match('/^09[0-9]{9}$/', $mobile)) {
            throw new InvalidArgumentException('Invalid mobile number format.');
        }
    }

    public static function validateEmail(?string $email): void
    {
        if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format.');
        }
    }

    public static function validateCurrency(?string $currency): void
    {
        $validCurrencies = ['IRR', 'IRT'];
        if ($currency !== null && !in_array($currency, $validCurrencies)) {
            throw new InvalidArgumentException('Invalid currency format. Allowed values are "IRR" or "IRT".');
        }
    }

    public static function validateWages(?array $wages): void
    {
        if ($wages !== null) {
            foreach ($wages as $wage) {
                if (!isset($wage['iban']) || !preg_match('/^IR[0-9]{2}[0-9A-Z]{1,24}$/', $wage['iban'])) {
                    throw new InvalidArgumentException('Invalid IBAN format in wages.');
                }
                if (!isset($wage['amount']) || $wage['amount'] <= 0) {
                    throw new InvalidArgumentException('Wage amount must be greater than zero.');
                }
                if (!isset($wage['description']) || strlen($wage['description']) > 255) {
                    throw new InvalidArgumentException('Wage description must be provided and less than 255 characters.');
                }
            }
        }
    }

    public static function validateTerminalId(string $terminalId): void
    {
        if (empty($terminalId)) {
            throw new InvalidArgumentException('Terminal ID is required.');
        }
    }

    public static function validateFilter(?string $filter): void
    {
        if ($filter !== null) {
            $validFilters = ['PAID', 'VERIFIED', 'TRASH', 'ACTIVE', 'REFUNDED'];
            if (!in_array($filter, $validFilters)) {
                throw new InvalidArgumentException('Invalid filter value.');
            }
        }
    }

    public static function validateLimit(?int $limit): void
    {
        if ($limit !== null && $limit <= 0) {
            throw new InvalidArgumentException('Limit must be a positive integer.');
        }
    }

    public static function validateOffset(?int $offset): void
    {
        if ($offset !== null && $offset < 0) {
            throw new InvalidArgumentException('Offset must be a non-negative integer.');
        }
    }

    public static function validateCardPan(?string $cardPan): void
    {
        if ($cardPan !== null && !preg_match('/^[0-9]{16}$/', $cardPan)) {
            throw new InvalidArgumentException('Invalid card PAN format. It should be a 16-digit number.');
        }
    }

    public static function validateSessionId(string $sessionId): void
    {
        if (empty($sessionId)) {
            throw new InvalidArgumentException('Session ID is required.');
        }
    }

    public static function validateMethod(string $method): void
    {
        $validMethods = ['PAYA', 'CARD'];
        if (!in_array($method, $validMethods)) {
            throw new InvalidArgumentException('Invalid method. Allowed values are "PAYA" or "CARD".');
        }
    }

    public static function validateReason(string $reason): void
    {
        $validReasons = [
            'CUSTOMER_REQUEST',
            'DUPLICATE_TRANSACTION',
            'SUSPICIOUS_TRANSACTION',
            'OTHER'
        ];
        if (!in_array($reason, $validReasons)) {
            throw new InvalidArgumentException('Invalid reason. Allowed values are "CUSTOMER_REQUEST", "DUPLICATE_TRANSACTION", "SUSPICIOUS_TRANSACTION", or "OTHER".');
        }
    }
}
