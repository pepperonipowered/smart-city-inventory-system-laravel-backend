<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Validation\ValidationException;

class PasswordResetCodeController extends Controller
{
    /**
     * Handle an incoming password reset code request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        // Check if user exists in the database
        if (!DB::table('users')->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => ['We can\'t find a user with that email address. -Autumn'],
            ]);
        }

        // Generate a 6-digit reset code
        $code = random_int(100000, 999999);

        // Store the code in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => bcrypt($code),  // Store the encrypted code
                'created_at' => Carbon::now(),
            ]
        );

        // Send the custom reset code email
        Mail::to($email)->send(new PasswordResetCodeMail($code));

        // Return a response indicating the email was sent
        return response()->json(['message' => 'A 6-digit code has been sent to your email.']);
    }
}
