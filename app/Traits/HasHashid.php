<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasHashid
{
    /**
     * Get the value of the model's route key.
     * Overriding for URL Hardening (Encryption).
     */
    public function getRouteKey()
    {
        $id = $this->getKey();
        if (!$id) return null;

        $key = substr(config('app.key'), 0, 16);
        $encrypted = openssl_encrypt((string)$id, 'AES-128-ECB', $key);
        
        // Return as base64 but make it URL safe (strip padding and replace chars)
        return str_replace(['+', '/', '='], ['-', '_', ''], $encrypted);
    }

    /**
     * Retrieve the model for a bound value.
     * Overriding for URL Hardening (Decryption).
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $key = substr(config('app.key'), 0, 16);
            
            // Restore base64 characters
            $base64 = str_replace(['-', '_'], ['+', '/'], $value);
            
            // Restore padding if needed
            $padding = strlen($base64) % 4;
            if ($padding > 0) {
                $base64 .= str_repeat('=', 4 - $padding);
            }

            $decrypted = openssl_decrypt($base64, 'AES-128-ECB', $key);
            
            if (!$decrypted) return null;

            return $this->where($field ?? $this->getKeyName(), $decrypted)->first();
        } catch (\Exception $e) {
            Log::warning("Hashid Decryption Failed: " . $e->getMessage());
            return null;
        }
    }
}
