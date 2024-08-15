<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback after user authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Use stateless to avoid session state issues
            $googleUser = Socialite::driver('google')->user();

            // Retrieve user details from Google
            $email = $googleUser->getEmail();
            $name = $googleUser->getName();

            // Check if the user already exists in the database
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Create a new user if it does not exist
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)), // Generate a random password
                    // Additional user data...
                ]);
            }

            // Generate a personal access token for the user
            $tokenResult = $user->createToken('Personal Access Token');

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at->toDateTimeString(),
                'user' => $user, // Include user details in the response
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to authenticate user', 'message' => $e->getMessage()], 400);
        }
    }
}
