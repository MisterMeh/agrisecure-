<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $recentUploads = File::where('created_at', '>=', Carbon::now()->subDay())->count();
        $totalFiles = File::count();
        $registeredEmployees = User::where('role', 0)->where('is_deleted', 0)->count();
        return view('admin.dashboard', [
            'recentUploads' => $recentUploads,
            'totalFiles' => $totalFiles,
            'registeredEmployees' => $registeredEmployees,
        ]);
    }
}
