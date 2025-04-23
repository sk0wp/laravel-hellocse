<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function create(CreateCommentRequest $request): Response
    {
        try {
            $comment = new Comment();
            $comment->content = $request->input('content');
            $comment->profile_id = $request->input('profile_id');
            $comment->administrator_id = $request->input('administrator_id');
            $comment->save();

            return response()->json([
                'success' => true,
                'message' => 'Comment created successfully',
                'data' => $comment
            ], Response::HTTP_CREATED); // 201 = Created
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Create comment failed : %s', $exception->getMessage()),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
