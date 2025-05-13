<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class PostController extends Controller
{
    private $markdownConverter;
    
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        
        // Configure CommonMark with GitHub-Flavored Markdown
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $this->markdownConverter = new CommonMarkConverter([], $environment);
    }
    
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::with(['user'])
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:5120', // 5MB max size
            'featured_image_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);
        
        // Process the image if uploaded
        if ($request->hasFile('featured_image')) {
            $featured_image_url = $this->processAndStoreImage($request->file('featured_image'));
            $validated['featured_image_url'] = $featured_image_url;
        }
        
        // Create the post
        $post = Auth::user()->posts()->create($validated);
        
        return redirect()->route('posts.show', $post->uuid)
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post->increment('view_count');
        
        // Convert markdown to HTML
        $htmlContent = $this->markdownConverter->convert($post->content)->getContent();
        
        // Get like status for current user
        $userLike = null;
        if (Auth::check()) {
            $userLike = $post->likes()
                ->where('user_id', Auth::id())
                ->first();
        }
        
        // Get comments
        $comments = $post->comments()
            ->with('user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('posts.show', compact('post', 'htmlContent', 'userLike', 'comments'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Check if user owns the post
        $this->authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if user owns the post
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:5120', // 5MB max size
            'featured_image_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image_url && Str::contains($post->featured_image_url, '/storage/app/public/cached/')) {
                $oldPath = str_replace(url('/storage/app/public/'), '', $post->featured_image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $featured_image_url = $this->processAndStoreImage($request->file('featured_image'));
            $validated['featured_image_url'] = $featured_image_url;
        }
        
        $post->update($validated);
        
        return redirect()->route('posts.show', $post->uuid)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->featured_image_url && Str::contains($post->featured_image_url, '/storage/app/public/cached/')) {
            $path = str_replace(url('/storage/app/public/'), '', $post->featured_image_url);
            Storage::disk('public')->delete($path);
        }
        
        $post->delete();
        
        return redirect()->route('home')
            ->with('success', 'Post deleted successfully!');
    }
    
    /**
     * List all posts by the authenticated user.
     */
    public function myPosts()
    {
        $posts = Auth::user()->posts()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('posts.my-posts', compact('posts'));
    }
    
    /**
     * Process and store an uploaded image.
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @return string The URL to the stored image
     */
    private function processAndStoreImage($image)
    {
        $datePath = now()->format('Y/m/d/H_i_s');
        $directory = "cached/{$datePath}";
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $img = Image::make($image);
        $img->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->encode($image->getClientOriginalExtension(), 75);

        Storage::disk('public')->makeDirectory($directory);
        Storage::disk('public')->put($directory . '/' . $filename, $img->stream());

        return asset('storage/' . $directory . '/' . $filename);
    }
}