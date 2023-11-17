<?php

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationVerifier;
use Laravel\Fortify\Features;

return [
'features' => [
    Features::twoFactorAuthentication([
        'confirmPassword' => true,
    ]),
],
];
