<?php

return [
    "production" => env("AUTHORIZE_NET_PRODUCTION_MODE", false),
    "login_id" => env('AUTHORIZE_NET_LOGIN_ID', null),
    "transaction_key" => env('AUTHORIZE_NET_TRANSACTION_KEY', null)
];
