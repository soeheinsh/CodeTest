<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyLikedPostException;
use App\Exceptions\UserLikeOwnPostException;
use App\Http\Requests\PostToggleReactionRequest;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
class PostController extends Controller
{
    public function list(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 1); 

        $query = Post::orderBy('id', 'desc');

        $totalItems = $query->count();
        $cars = paginateData($query, $page, $perPage, $totalItems);
        
        $post_collection = new PostCollection($cars['data']);
        $post_collection->additional(['meta' => $cars['meta']]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Get all posts list successful',
            'data' => $post_collection,
        ]);
    }

    public function toggleReaction(PostToggleReactionRequest $request)
    {
        $postId = $request->validated('post_id');
        $isLike = $request->validated('like');
        $user = Auth::user();

        try {
            $post = Post::with('likes')->findOrFail($postId);

            // Check if the user is trying to like their own post
            if (Gate::denies('like-post', $post)) {
                throw new UserLikeOwnPostException();
            }

            // Check if the user already liked the post
            $userLike = $post->likes()->where('user_id', $user->id)->first();

            if ($userLike) {
                // If the user is trying to like the post again
                if ($isLike) {
                    throw new UserAlreadyLikedPostException();
                }

                // Unlike the post
                $userLike->delete();

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'You unliked this post successfully',
                ]);
            }

            // Like the post
            if ($isLike) {
                $post->likes()->create([
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'You liked this post successfully',
                ]);
            }

            // If the request is to unlike but the post is not liked
            return response()->json([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'You cannot unlike a post you haven\'t liked',
            ], Response::HTTP_CONFLICT);

        } catch (UserLikeOwnPostException $e) {
            return response()->json([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'You cannot like your own post',
            ], Response::HTTP_FORBIDDEN);
        } catch (UserAlreadyLikedPostException $e) {
            return response()->json([
                'status' => Response::HTTP_CONFLICT,
                'message' => 'You have already liked this post',
            ], Response::HTTP_CONFLICT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Post not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
