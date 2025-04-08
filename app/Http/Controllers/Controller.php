<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function checkAdminAuth()
    {
        if (!auth()->user() || auth()->user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sharedDataForViews()
    {
        return [
            'appName' => 'Your App Name',
            'currentYear' => now()->year,
        ];
    }
}
