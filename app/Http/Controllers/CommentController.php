<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 18/09/16
 * Time: 1:37 PM
 */

namespace App\Http\Controllers;

use App\Comment;
use App\Publication;
use App\Transformer\CommentTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {

    /**
     * @param Response
     */
    public function __construct(Response $response) {
        $this->response = $response;
        $this->middleware('basicauth', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($publicationId) {
        $publication = Publication::findOrFail($publicationId);
        $comments = $publication->comments()->get();

        return $this->response->withCollection($comments, new CommentTransformer);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, $publicationId) {
        $this->validate($request, [
            'message' => 'required',
        ]);

        try {
            $user_id = Auth::user()->id;

            $comment = new Comment([
                'message' => $request->input('message'),
                'user_id' => $user_id
            ]);

            $publication = Publication::findOrFail($publicationId);
            $publication->comments()->save($comment);

            return $this->response->withItem($comment, new CommentTransformer);

        } catch (\Exception $error) {
            return $this->response->errorInternalError($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id, $publicationId) {
        try {
            $publication = Publication::findOrFail($publicationId);
            $comment = $publication->comments()
                ->where('id', $id)
                ->firstOrFail();

            return $this->response->withItem($comment, new CommentTransformer);

        } catch (\Exception $error) {
            return $this->response->errorInternalError($error->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id, $publicationId) {

        try {
            $user_id = Auth::user()->id;
            $publication = Publication::findOrFail($publicationId);
            $comment = $publication->comments()
                ->where('id', $id)
                ->firstOrFail();

            if ($comment->user_id == $user_id) {
                $comment->message = $request->input('message', $comment->message);
                $comment->save();

                return $this->response->withItem($comment, new CommentTransformer);

            } else {
                return $this->response->errorUnauthorized('Unauthorized');
            }

        } catch (\Exception $error) {
            return $this->response->errorInternalError($error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id, $publicationId) {
        try {
            $user_id = Auth::user()->id;
            $publication = Publication::findOrFail($publicationId);
            $comment = $publication->comments()
                ->where('id', $id)
                ->firstOrFail();

            if ($comment->user_id == $user_id) {
                $comment->delete();

                return response()->json(['data' => ['delete' => TRUE]]);
            } else {
                return $this->response->errorUnauthorized('Unauthorized');
            }

        } catch (\Exception $error) {
            return $this->response->errorInternalError($error->getMessage());
        }
    }
}