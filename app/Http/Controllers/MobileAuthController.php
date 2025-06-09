<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:6',
            ], [
                // 'firstName.required' => 'The first name is required.',
                'lastName.required' => 'The last name is required.',
                'email.required' => 'The email is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email is already in use.',
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 6 characters long.',
            ]);
        
            $user = User::create([
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully.',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ], [
                'email.required' => 'The email is required.',
                'email.email' => 'Please provide a valid email address.',
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 6 characters long.',
            ]);
    
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not exist for this email.',
                ], 404);
            }
    
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request) {
         // Log: Request received
        Log::info('Logout request received.');
        $user = $request->user();
        Log::info('Authenticated user:', ['user' => $user]);
        // Check if user is authenticated
        if (!$user) {
            Log::warning('Logout failed: user not authenticated.');
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }
    
        // Check if the user has an active token
        $token = $user->currentAccessToken();
        Log::info('Access token:', ['token' => $token]);
        if (!$token) {
            Log::warning('Logout failed: no active token.');
            return response()->json([
                'message' => 'User already logged out or no active session',
            ], 400);
        }
    
        try {
            // Delete the access token
            $token->delete();
            Log::info('Token deleted successfully.');
    
            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Logout exception', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to logout. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
   public function getUser(Request $request) {
        return $request->user();
   }
}
