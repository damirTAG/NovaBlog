<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        
        $comment = new Comment([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $request->has('parent_id') ? $validated['parent_id'] : null,
        ]);
        
        $comment->save();
        
        return redirect()->back()->with('success', 'Comment added successfully!');
    }
    
    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user owns the comment
        $this->authorize('update', $comment);
        
        $validated = $request->validate([
            'content' => 'required',
        ]);
        
        $comment->update($validated);
        
        return redirect()->back()->with('success', 'Comment updated successfully!');
    }
    
    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Check if user owns the comment
        $this->authorize('delete', $comment);
        
        $comment->delete();
        
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}