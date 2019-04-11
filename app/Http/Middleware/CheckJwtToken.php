<?php

namespace App\Http\Middleware;

use Closure;

class CheckJwtToken
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
        $jwt = explode(" ",$request->header('Authorization'))[1];
        $header = explode('.',$jwt)[0];
        $payload = explode('.',$jwt)[1];
        $signature = explode('.',$jwt)[2];

        $generatedSignature = $this->createSignatureUsingUserCred($header, $payload);

        if($signature === $generatedSignature){
            return $next($request);
        }else{
            
            return abort(403, 'Unauthorized action.');
        }
    }

    private function createSignatureUsingUserCred($header, $payload){
        return $this->base64_url_encode(hash_hmac('sha256', "$header.$payload", "averylongpassword123!@#", true));

    }
    private function base64_url_encode($input) {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
}
