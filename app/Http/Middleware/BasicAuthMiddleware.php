<?php

namespace App\Http\Middleware;

use Closure;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Support\Facades\Auth;

class BasicAuthMiddleware {

    /**
     * Create a new middleware instance.
     *
     * @param  EllipseSynergie\ApiResponse\Contracts\Response $response
     * @return void
     */
    public function __construct(Response $response) {
        $this->response = $response;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::onceBasic() !== NULL) {
            $headers = ['WWW-Authenticate' => 'Basic'];

            return $this->response->errorUnauthorized('Invalid credentials.', $headers);
        }

        return $next($request);
    }
}
