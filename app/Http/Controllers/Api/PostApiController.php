<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Post API",
 *     description="Simple API documentation for providing full posts data"
 * )
 *
 * @OA\Tag(
 *     name="Posts",
 *     description="Operations about blog posts"
 * )
 */
class PostApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     operationId="getPostsList",
     *     tags={"Posts"},
     *     summary="Get list of posts",
     *     description="Returns paginated list of posts",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $posts = Post::latest()->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{uuid}",
     *     operationId="getPostByUuid",
     *     tags={"Posts"},
     *     summary="Get post by UUID",
     *     description="Returns a single post",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="8 symbols UUID of the post",
     *         @OA\Schema(type="string", example="c2d33f86")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post found",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */
    public function show($uuid)
    {
        $post = Post::where('uuid', $uuid)->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }
}
