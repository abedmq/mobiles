<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Models\MobileApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    function GenerateToken(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:android,ios',
            'firebase_token' => 'required',
        ]);

        $data['token'] = md5(time()) . "|" . sha1(time());
        $data['ip'] = $request->ip();
        $data['country_code'] = get_country_from_ip($request->ip());
        $data['mac'] = $request->mac;
        $data['type'] = $request->type;
        $data['firebase_token'] = $request->firebase_token;
        $data['last_check'] = Carbon::now();
        $data['mobile'] = @$request->mobile ?: null;

        $mobileApp = MobileApp::create($data);
        return $this->response()->resource(new UserProfileResource($mobileApp));
    }

}
