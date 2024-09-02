<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyLikedPostException;
use App\Exceptions\UserLikeOwnPostException;
use App\Http\Requests\PostToggleReactionRequest;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use App\ResponseFormat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        return ResponseFormat::success($post_collection, 'Get all posts list successful', 200);
    }

    public function toggleReaction(PostToggleReactionRequest $request)
    {
        try {
            $post = Post::query()
                ->with([
                    'likes' => function (HasMany $query) {
                        $query->whereBelongsTo(Auth::user());
                    },
                ])
                ->findOrFail($request->validated('post_id'));

            // user tries to like his own post
            throw_if(Gate::denies('like-post', $post), UserLikeOwnPostException::class);

            // user already liked the post
            if ($post->likes->isNotEmpty()) {
                // reaction is like the post
                throw_if($request->boolean('like'), UserAlreadyLikedPostException::class);

                $post->likes->map->delete();

                return response()->json([
                    'status'  => Response::HTTP_OK,
                    'message' => 'You unlike this post successfully',
                ]);
            }

            $post->likes()->create([
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status'  => Response::HTTP_OK,
                'message' => 'You like this post successfully',
            ]);
        } catch (UserLikeOwnPostException $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'You cannot like your post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (UserAlreadyLikedPostException $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'You already liked this post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'model not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
