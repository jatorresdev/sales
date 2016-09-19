<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 15/09/16
 * Time: 5:20 PM
 */

namespace App\Http\Controllers;

use App\Publication;
use App\Transformer\PublicationTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PublicationController extends Controller {

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
    public function index() {
        $publications = Publication::with('user')->get();

        return $this->response->withCollection($publications, new PublicationTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $documentRoot = $request->server('DOCUMENT_ROOT');
        $serverName = 'http://' . $request->server('SERVER_NAME');
        $folderImage = '/assets/imgs/publication';

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'city' => 'required|max:100',
        ]);

        try {
            $user_id = Auth::user()->id;

            $publication = new Publication;

            $publication->title = $request->input('title');
            $publication->description = $request->input('description');
            $publication->city = $request->input('city');
            $publication->user_id = $user_id;

            $photo = $request->file('photo');
            if ($request->hasFile('photo') && $photo->isValid()) {
                $request->file('photo')
                    ->move($documentRoot . $folderImage, $photo->getClientOriginalName());

                $publication->photo = $serverName . $folderImage . '/' . $photo->getClientOriginalName();
            }

            $publication->save();

            return $this->response->withItem($publication, new PublicationTransformer);

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
    public function show($id) {
        try {
            $publication = Publication::findOrFail($id);

            return $this->response->withItem($publication, new PublicationTransformer);

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
    public function update(Request $request, $id) {
        $documentRoot = $request->server('DOCUMENT_ROOT');
        $serverName = 'http://' . $request->server('SERVER_NAME');
        $folderImage = '/assets/imgs/publication';

        $this->validate($request, [
            'city' => 'max:100'
        ]);

        try {
            $user_id = Auth::user()->id;
            $publication = Publication::findOrFail($id);

            if ($publication->user_id == $user_id) {
                $publication->title = $request->input('title', $publication->title);
                $publication->description = $request->input('description', $publication->description);
                $publication->city = $request->input('city', $publication->city);

                $photo = $request->file('photo');
                if ($request->hasFile('photo') && $photo->isValid()) {
                    $request->file('photo')
                        ->move($documentRoot . $folderImage, $photo->getClientOriginalName());

                    $publication->photo = $serverName . $folderImage . '/' . $photo->getClientOriginalName();
                }

                $publication->save();
                return $this->response->withItem($publication, new PublicationTransformer);

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
    public function destroy($id) {

        try {
            $user_id = Auth::user()->id;
            $publication = Publication::findOrFail($id);

            if ($publication->user_id == $user_id) {
                $publication->delete();

                return response()->json(['data' => ['delete' => TRUE]]);
            } else {
                return $this->response->errorUnauthorized('Unauthorized');
            }

        } catch (\Exception $error) {
            return $this->response->errorInternalError($error->getMessage());
        }
    }

}