<?php

if (!function_exists('hitung_ppn')) {
    function hitung_ppn($total_harga)
    {
        return $total_harga * 0.11;
    }
}

if (!function_exists('hitung_biaya_admin')) {
    function hitung_biaya_admin($total_harga)
    {
        if ($total_harga <= 20000000) {
            return $total_harga * 0.006;
        } elseif ($total_harga <= 40000000) {
            return $total_harga * 0.008;
        } else {
            return $total_harga * 0.010;
        }
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $code = strtoupper(trim($voucher_code));
        switch ($code) {
            case 'FLASH10':
                return $total_harga * 0.10;
            case 'FLASH15':
                return $total_harga * 0.15;
            case 'MEMBER20':
                return $total_harga * 0.20;
            default:
                return 0.0;
        }
    }
}
