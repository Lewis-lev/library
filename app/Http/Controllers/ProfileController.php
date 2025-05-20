<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['nullable', 'regex:/^(\+?\d{10,15})$/'],
            'address' => ['nullable', 'string'],
        ]);

        $user->fill($request->only([
            'name',
            'email',
            'phone_number',
            'address',
        ]));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('profile.edit')
                ->with('error', 'You must verify your email address before editing your profile.');
        }

        if ($request->hasFile('profile_picture')) {
            // Remove previous image if exists
            if ($user->profile_picture && Storage::disk('r2')->exists('img/profile_pict/' . $user->profile_picture)) {
                Storage::disk('r2')->delete('img/profile_pict/' . $user->profile_picture);
            }

            $image = $request->file('profile_picture');
            $filename = uniqid('profile_') . '.webp';
            $relativePath = 'img/profile_pict/' . $filename;

            try {
                // Convert to webp using GD
                $imgContent = file_get_contents($image->getRealPath());
                $gdImage = @imagecreatefromstring($imgContent);

                if ($gdImage) {
                    // Output to a temporary stream in webp format
                    ob_start();
                    imagewebp($gdImage);
                    $webpData = ob_get_clean();
                    imagedestroy($gdImage);

                    // Upload to R2 using Laravel Storage
                    Storage::disk('r2')->put($relativePath, $webpData, 'public');

                    // Assign filename (no subdir) to DB
                    $user->profile_picture = $filename;
                } else {
                    return redirect()->back()->withErrors(['profile_picture' => 'Failed to process image. Please upload a valid image file.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['profile_picture' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        // Handle password update if provided
        $currentPasswordFilled = $request->filled('current_password');
        $newPasswordFilled = $request->filled('password');
        $confirmPasswordFilled = $request->filled('password_confirmation');

        if ($currentPasswordFilled || $newPasswordFilled || $confirmPasswordFilled) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'Profile updated!');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function userList()
    {
        $user = User::All();

        return view('auth.user-list', compact('user'));
    }

    public function delete(User $user_id)
    {
        // Prevent deletion of admin users
        if ($user_id->role === 'admin') {
            return redirect()->route('auth.user-list')->with('error', 'Admin users cannot be deleted.');
        }

        // Delete associated image file from R2 if exists
        if ($user_id->profile_picture && Storage::disk('r2')->exists('img/profile_pict/' . $user_id->profile_picture)) {
            Storage::disk('r2')->delete('img/profile_pict/' . $user_id->profile_picture);
        }

        $user_id->delete();

        return redirect()->route('auth.user-list')->with('success', 'User deleted successfully.');
    }


    public function userView($userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();
        return view('profile.view', compact('user'));
    }
}
