<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReportsAuthController extends Controller
{
    public function showLogin(): View
    {
        $branches = Branch::orderBy('name')->get();

        return view('reports.login', compact('branches'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::validate(['email' => $request->email, 'password' => $request->password])) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $branchId = $request->filled('branch_id') ? (int) $request->branch_id : null;

        if ($user->role !== 'admin') {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => 'These credentials do not have report access.',
            ]);
        }

        if ($branchId === null) {
            if ($user->branch_id !== null) {
                RateLimiter::hit($this->throttleKey($request));

                throw ValidationException::withMessages([
                    'branch_id' => 'Please select the branch assigned to this account.',
                ]);
            }
        } elseif ((int) $user->branch_id !== $branchId) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'branch_id' => 'These credentials do not belong to the selected branch.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        RateLimiter::clear($this->throttleKey($request));

        $dashboardUrl = $user->branch
            ? route('admin.branch-dashboard', ['slug' => $user->branch->slug()], absolute: false)
            : route('admin.dashboard', absolute: false);

        return redirect()->intended($dashboardUrl);
    }

    private function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->email) . '|' . $request->ip());
    }
}
