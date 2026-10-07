<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Central Dashboard
     */
    public function index()
    {
        $data['title'] = 'Dashboard || ' . get_project_name();
        $data['header'] = [
            'title' => 'Dashboard Overview',
            'subtitle' => 'Live Central Pool & Flat Expense Analytics',
            'breadcrumb' => [
                'Home' => route('dashboard'),
                'Dashboard' => '',
            ]
        ];

        $data['js'] = ['comman_function.js', 'dashboard.js'];
        $data['funinit'] = ['Dashboard.init()'];

        // Summary stats for the view
        $data['stats'] = [
            'total_pool_balance' => 48000.00,
            'current_balance'    => 32450.00,
            'total_expenses'     => 15550.00,
            'active_members'     => 8,
            'pending_advances'   => 2,
        ];

        return view('backend.pages.dashboard.index', $data);
    }
}
