<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function Index()
    {
        return view('admin.dashboard');
    }

    public function GetUsers()
    {
        return 'hello';
    }
}
