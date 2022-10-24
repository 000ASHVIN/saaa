<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'peach/notify',
        'peach/validate',
        'instant-eft/notify',
        'instant-eft/webhook/notify',
        'payment/afterCheckThreeDs',
        'payment/checkThreeDs',
        'auth/register',
        'bulkmail/webhook',
        'api/v1.0/*'
    ];
}
