<?php

return [
    'minutes_to_resend'     => env('minutes_to_resend', 5),
    'max_provider_km'       => env('max_provider_km', 500),
    'FACEBOOK_IMPORT_COUNT'       => env('FACEBOOK_IMPORT_COUNT', 1000),
    'max_user_active_order' => intval(env('max_user_active_order', 10)),
];
