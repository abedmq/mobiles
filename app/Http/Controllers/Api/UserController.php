<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobileResource;
use App\Http\Resources\UserProfileResource;
use App\Models\Language;
use App\Models\Message;
use App\Models\User;
use App\Models\UserSetting;
use App\Notifications\SendCodeNotification;
use App\Rules\Mobile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Propaganistas\LaravelPhone\PhoneNumber;
use function GuzzleHttp\Psr7\str;

class UserController extends Controller
{

    //
    function profile(Request $request)
    {
        return $this->response()->resource(new UserProfileResource(user()));
    }

    function check(Request $request)
    {
        $user = user();
        $data['last_check'] = Carbon::now();
        $data['ip'] = $request->ip();
        if ($request->firebase_token)
            $data['firebase_token'] = $request->firebase_token;
        if ($request->mobile)
            $data['mobile'] = $request->mobile;
        $user->update($data);
        return $this->response()->resource(new UserProfileResource($user));
    }

    function contacts(Request $request)
    {
        $this->validate($request, [
            'contacts' => 'required|array',
            'contacts.0.name' => 'required',
            'contacts.*.mobile' => 'required',
        ]);
        $contacts = [];
        $fullMobiles = [];
        foreach ($request->contacts as $val) {
            $mobile = PhoneNumber::make(data_get($val, 'mobile'), user()->country_code);
            $fullMobiles[] = $mobile->formatInternational();
            $contacts[] = [
                'mobile' => clean_mobile($mobile->formatE164()),
                'country_code' => $mobile->getCountry(),
                'full_mobile' => clean_mobile($mobile->formatInternational()),
                'name' => data_get($val, 'name')
            ];
        }
        user()->contacts()->whereIn('full_mobile', $fullMobiles)->delete();
        user()->contacts()->createMany($contacts);
        return $this->response()->success();
    }

    function search(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required'
        ]);
        try {
            $mobile = PhoneNumber::make($request->mobile, user()->country_code);

            $facebook = \App\Models\Facebook\User::where('mobile', clean_mobile($mobile->formatInternational()))->first();
            if (!$facebook) {
                if (user()->country_code == 'PS') {
                    $country = 'IL';
            } else {
                    $country = 'PS';
            }
                $mobile = PhoneNumber::make($request->mobile, $country);
                $facebook = \App\Models\Facebook\User::where('mobile', clean_mobile($mobile->formatInternational()))->first();
            }
        } catch (\Exception $e) {
            $facebook = new \App\Models\Facebook\User();
        }
        return $this->response()->resource(new MobileResource($facebook));
    }

}
