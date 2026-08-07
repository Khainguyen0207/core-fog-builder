<?php

namespace App\Services;

class VietQR
{
    private static string $baseUrl = 'https://img.vietqr.io/image/';

    private static string $format = 'compact2';

    public static function generateQRCode(array $metaData, string $bankName, string $accountNumber)
    {
        $bankName = strtolower(str_replace(' ', '', $bankName));
        $endPoint = self::$baseUrl.$bankName.'-'.$accountNumber.'-'.self::$format.'.png?';

        foreach ($metaData as $key => $value) {
            $endPoint .= $key.'='.urlencode($value).'&';
        }

        return $endPoint;
    }
}
