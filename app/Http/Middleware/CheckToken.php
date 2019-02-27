<?php

namespace App\Http\Middleware;

use Closure;
use App\Core\Response;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->header('x-token'))) {
            $resp = new Response();
            return $resp->failed(Response::RESP_CODE_BAD_REQUEST, [Response::ERR_CODE_NO_TOKEN])->toApiOutput();
        }

        return $next($request);
    }
}