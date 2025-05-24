<?php

namespace App\Services;

use libphonenumber\PhoneNumberUtil;

class CountryCodes
{
    public static function getCountriesWithCodes(): array
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $countries = [];

        $supportedRegions = $phoneUtil->getSupportedRegions();

        foreach ($supportedRegions as $countryCode) {
            $countryName = self::getCountryName($countryCode);
            $phoneCode = $phoneUtil->getCountryCodeForRegion($countryCode);
            $countries[$countryCode] = [
                'name' => $countryName,
                'code' => $phoneCode,
                'flag' => strtolower($countryCode)
            ];
        }

        // Sort by country name
        usort($countries, fn($a, $b) => strcmp($a['name'], $b['name']));

        return $countries;
    }

    protected static function getCountryName(string $countryCode): string
    {
        $countryNames = [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            // Add all countries you need...
        ];

        return $countryNames[$countryCode] ?? $countryCode;
    }
}
