<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Toggle like/dislike for a post.
     */
    public function toggle(Request $request, Post $post)
    {
        $validated = $request->validate([
            'is_like' => 'required|boolean',
        ]);
        
        $like = $post->likes()
            ->where('user_id', Auth::id())
            ->first();
        
        if ($like) {
            if ($like->is_like == $validated['is_like']) {
                // If user clicks the same button again, remove the like/dislike
                $like->delete();
                $message = $validated['is_like'] ? 'Like removed!' : 'Dislike removed!';
            } else {
                // Change like to dislike or vice versa
                $like->update(['is_like' => $validated['is_like']]);
                $message = $validated['is_like'] ? 'Changed to like!' : 'Changed to dislike!';
            }
        } else {
            // Create new like/dislike
            $post->likes()->create([
                'user_id' => Auth::id(),
                'is_like' => $validated['is_like'],
            ]);
            $message = $validated['is_like'] ? 'Post liked!' : 'Post disliked!';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'likes_count' => $post->fresh()->likes_count,
                'dislikes_count' => $post->fresh()->dislikes_count,
                'message' => $message
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
}