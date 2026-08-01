<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeGeocodingService
{
    /**
     * Convert latitude and longitude to a human-readable address for FREE.
     */
    public function getAddressFromCoords(float $lat, float $lng): ?string
    {
        $url = "https://nominatim.openstreetmap.org/reverse";

        try {
            $response = Http::withHeaders([
                // REQUIRED: Identify your application or provide an email
                'User-Agent' => 'MyLaravelApp (contact@mywebsite.com)',
            ])->timeout(8)->get($url, [
                'lat'    => $lat,
                'lon'    => $lng,
                'format' => 'json',
                'zoom'   => 18,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['display_name'])) {
                    return $this->friendlyAddress($data);
                }
            }
        } catch (\Exception $e) {
            Log::error("Free geocoding error: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Build a clean street-level address, skipping OSM administrative noise
     * (e.g. "CMWSSB Division 101, Ward 101, Zone 8").
     */
    private function friendlyAddress(array $data): string
    {
        $a = $data['address'] ?? [];
        $parts = [];

        $street = $a['road'] ?? $a['footway'] ?? $a['pedestrian'] ?? $a['cycleway'] ?? '';
        $streetLine = trim(($a['house_number'] ?? '') . ' ' . $street);
        if ($streetLine !== '') {
            $parts[] = $streetLine;
        }

        $area = $a['neighbourhood'] ?? $a['suburb'] ?? $a['townland'] ?? $a['quarter'] ?? '';
        if ($area !== '') {
            $parts[] = $area;
        }

        $city = $a['city'] ?? $a['town'] ?? $a['village'] ?? $a['municipality'] ?? $a['county'] ?? '';
        if ($city !== '') {
            $parts[] = $city;
        }

        foreach (['state', 'postcode', 'country'] as $key) {
            if (($a[$key] ?? '') !== '') {
                $parts[] = $a[$key];
            }
        }

        return implode(', ', $parts);
    }
}
