<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the admin profile.
     */
    public function edit()
    {
        // In a real application, you would fetch current admin data 
        // from Firebase or Auth::user() here to pass to the view.
        return view('admin.profile');
    }

    /**
     * Update the admin profile details.
     */
    public function update(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8', // Password is optional
        ]);

        try {
            // 2. Prepare Data for Update
            $updateData = [
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
            ];

            // Only update password if the user actually typed a new one
            if ($request->filled('password')) {
                // If using Laravel's local DB:
                // $updateData['password'] = Hash::make($request->input('password'));
                
                // If using Firebase: 
                // You would use the Firebase Auth SDK to update password here.
            }

            // 3. TODO: FIREBASE INTEGRATION
            // Example Placeholder:
            // $database->getReference('admins/' . $adminId)->update($updateData);

            // 4. Return to the profile page with a success message
            return redirect()
                ->route('admin.profile')
                ->with('success', 'Admin profile updated successfully!');

        } catch (\Exception $e) {
            // If something goes wrong (e.g., connection issue), return with error
            return back()
                ->withInput()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
}