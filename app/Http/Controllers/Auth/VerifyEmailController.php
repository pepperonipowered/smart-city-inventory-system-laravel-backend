<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect(config('app.frontend_url') . '/login?error=invalid-user');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url') . '/email_verified');
        }

        if (hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return redirect(config('app.frontend_url') . '/email_verified');
        }

        return redirect(config('app.frontend_url') . '/login?error=invalid-hash');
    }
}
