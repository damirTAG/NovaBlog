<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }
    
    /**
     * Display the specified user profile.
     */
    public function show(User $user)
    {
        // Create profile if it doesn't exist
        if (!$user->profile) {
            $user->profile()->create([]);
        }
        
        $posts = $user->posts()
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        return view('users.show', compact('user', 'posts'));
    }
    
    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Create profile if it doesn't exist
        if (!$user->profile) {
            $user->profile()->create([]);
        }
        
        return view('users.edit', compact('user'));
    }
    
    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|max:255',
            'avatar_url' => 'nullable|url',
            'bio' => 'nullable|max:1000',
            'location' => 'nullable|max:255',
            'website' => 'nullable|url',
        ]);
        
        // Update user
        $user->update([
            'name' => $validated['name'],
            'avatar_url' => $validated['avatar_url'],
        ]);
        
        // Update or create profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $validated['bio'],
                'location' => $validated['location'],
                'website' => $validated['website'],
            ]
        );
        
        return redirect()->route('users.show', $user->id)
            ->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Search for users.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate(10);
            
        return view('users.search', compact('users', 'query'));
    }
}