<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index(?string $slug = null)
    {
        $user = Auth::user();
        $branch = $user && $user->branch_id ? $user->branch : null;

        $dashboardUrl = route('admin.dashboard');
        if ($branch) {
            $dashboardUrl = route('admin.branch-dashboard', ['slug' => $branch->slug()]);
        }

        if ($branch && $slug !== $branch->slug()) {
            return Redirect::to($dashboardUrl);
        }

        if (!$branch && $slug) {
            return Redirect::to($dashboardUrl);
        }

        return view('admin.dashboard', compact('user', 'branch', 'dashboardUrl'));
    }
}
