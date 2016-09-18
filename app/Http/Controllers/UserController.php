<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 17/09/16
 * Time: 11:03 PM
 */

namespace App\Http\Controllers;

use App\Transformer\UserTransformer;
use App\User;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    /**
     * @param Response
     */
    public function __construct(Response $response) {
        $this->response = $response;
        $this->middleware('basicauth', [
            'except' => [
                'store',
                'login',
                'logout'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|max:90',
            'last_name' => 'required|max:90',
            'email' => 'required|email|max:128',
            'cellphone' => 'required|max:30',
            'telephone' => 'max:30',
            'password' => 'required',
        ]);

        try {
            $user = new User;

            $user->name = $request->input('name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->cellphone = $request->input('cellphone');
            $user->telephone = $request->input('telephone', '');
            $user->password = $request->input('password');
            $user->password = app('hash')->make($request->input('password'));

            $user->save();
            return $this->response->withItem($user, new UserTransformer);

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'max:90',
            'last_name' => 'max:90',
            'email' => 'email|max:128',
            'cellphone' => 'max:30',
            'telephone' => 'max:30',
        ]);

        try {
            $id = Auth::user()->id;
            $user = User::find($id);

            $user->name = $request->input('name', $user->name);
            $user->last_name = $request->input('last_name', $user->last_name);
            $user->email = $request->input('email', $user->email);
            $user->cellphone = $request->input('cellphone', $user->cellphone);
            $user->telephone = $request->input('telephone', $user->telephone);
            $user->password = $request->input('password', $user->password);

            $user->save();
            return $this->response->withItem($user, new UserTransformer);

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
        //
    }

    public function login(Request $request) {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            return $this->response->withItem($user, new UserTransformer);
        }

        return $this->response->errorUnauthorized('Unauthorized');
    }

    public function logout() {
        Auth::logout();

        return response()->json(['data' => ['logout' => TRUE]]);
    }
}