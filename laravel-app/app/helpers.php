<?php

if (! function_exists('hc_auth')) {
    function hc_auth() {
        return app(\App\Support\HcAuth::class);
    }
}
