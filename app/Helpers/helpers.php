<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_project_name')) {
    /**
     * Get the project name
     */
    function get_project_name()
    {
        return config('app.name', 'Flat-Kharch');
    }
}

if (!function_exists('date_formate')) {
    /**
     * Date format: 03-Oct-2026
     */
    function date_formate($date)
    {
        return $date ? date('d-M-Y', strtotime($date)) : '-';
    }
}

if (!function_exists('date_formatee')) {
    /**
     * Date format: 03-10-2026
     */
    function date_formatee($date)
    {
        return $date ? date('d-m-Y', strtotime($date)) : '-';
    }
}

if (!function_exists('time_formate')) {
    /**
     * Time format: 04:30 PM
     */
    function time_formate($date)
    {
        return $date ? date('h:i A', strtotime($date)) : '-';
    }
}

if (!function_exists('date_time_formate')) {
    /**
     * Date and time format: 03-Oct-2026 04:30 PM
     */
    function date_time_formate($date)
    {
        return $date ? date('d-M-Y h:i A', strtotime($date)) : '-';
    }
}

if (!function_exists('ind_money_format')) {
    /**
     * Format number according to Indian Currency numbering system (e.g. 1,50,000.00)
     */
    function ind_money_format($money)
    {
        if ($money === null || $money === '') {
            return '0.00';
        }
        if (is_string($money)) {
            $money = preg_replace('/[^\d.-]/', '', $money);
        }
        $money = (float) $money;

        $decimal = (string) round($money - floor($money), 2);
        $money = floor($money);
        $length = strlen((string) $money);
        $m = '';
        $money = strrev((string) $money);
        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $m .= ',';
            }
            $m .= $money[$i];
        }
        $result = strrev($m);
        $decimal = preg_replace('/0\./i', '.', $decimal);
        $decimal = substr($decimal, 0, 3);
        if ($decimal != '0' && $decimal != '') {
            $result = $result . $decimal;
        } else {
            $result = $result . '.00';
        }
        return $result;
    }
}

if (!function_exists('format_rupee')) {
    /**
     * Format amount with Rupee symbol (e.g. ₹1,500.00)
     */
    function format_rupee($amount)
    {
        return '₹' . ind_money_format($amount);
    }
}

if (!function_exists('get_user_role_badge')) {
    /**
     * User role badge HTML (1: Admin, 2: Member)
     */
    function get_user_role_badge($role)
    {
        $role = (int) $role;
        if ($role === 1) {
            return '<span class="badge badge-light-primary fw-bold text-primary px-3 py-2">Admin</span>';
        }
        return '<span class="badge badge-light-info fw-bold text-info px-3 py-2">Member</span>';
    }
}

if (!function_exists('get_status_badge')) {
    /**
     * Status badge HTML (1: Active, 2: Inactive)
     */
    function get_status_badge($status)
    {
        if ($status === 1 || $status === '1' || strtoupper((string)$status) === 'ACTIVE') {
            return '<span class="badge badge-light-success text-success fw-semibold px-2 py-1">Active</span>';
        }
        if ($status === 2 || $status === '2' || strtoupper((string)$status) === 'INACTIVE') {
            return '<span class="badge badge-light-danger text-danger fw-semibold px-2 py-1">Inactive</span>';
        }
        
        $statusUpper = strtoupper((string)$status);
        return match ($statusUpper) {
            'PAID', 'APPROVED', 'SETTLED' => '<span class="badge badge-light-success text-success fw-semibold px-2 py-1">Active</span>',
            'PENDING' => '<span class="badge badge-light-warning text-warning fw-semibold px-2 py-1">Pending</span>',
            default => '<span class="badge badge-light-secondary text-secondary fw-semibold px-2 py-1">' . ucfirst((string)$status) . '</span>',
        };
    }
}

if (!function_exists('get_user_avatar')) {
    /**
     * Get profile avatar URL or default UI Avatar
     */
    function get_user_avatar($avatarPath = null, $name = 'User')
    {
        if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
            return asset('storage/' . $avatarPath);
        }
        $encodedName = urlencode($name);
        return "https://ui-avatars.com/api/?name={$encodedName}&background=0D8ABC&color=fff&rounded=true&bold=true";
    }
}
