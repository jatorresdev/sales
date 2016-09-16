<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 15/09/16
 * Time: 5:20 PM
 */

namespace app\Http\Controllers;

use App\Publication;
use Illuminate\Http\Request;


class PublicationController {

    public function getPublications() {
        $publications = Publication::all();
        return response()->json($publications);
    }

    public function getPublication($id) {
        $publication = Publication::find($id);
        return response()->json($publication);
    }

    public function savePublication(Request $request) {
        $publication = Publication::create($request->all());
        return response()->json($publication);
    }

    public function deletePublication($id) {
        $publication = Publication::find($id);
        $publication->delete();
        return response()->json([
            'success' => TRUE
        ]);
    }

    public function updatePublication(Request $request, $id) {
        $publication = Publication::find($id);
        $publication->title = $request->input('title');
        $publication->description = $request->input('description');
        $publication->save();
        return response()->json($publication);
    }

}