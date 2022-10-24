<?php

namespace App\Http;

use GuzzleHttp\Middleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\LogLastUserActivity::class,
        \App\Http\Middleware\RedirectRules::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'have_access' => \App\Http\Middleware\RedirectIfRoleIsSales::class,
        'contact-details' => \App\Http\Middleware\CheckIfHasContactNumber::class,
        'overdue-subscription' => \App\Http\Middleware\CheckSubscriptionOverdueStatus::class,
        'id-number-check' => \App\Http\Middleware\CheckIDNumberOnUserProfile::class,
        'CheckSuspendedStatus' => \App\Http\Middleware\CheckSuspendedStatus::class,
        'CheckProfessionalBody' => \App\Http\Middleware\CheckProfessionalBody::class,
        'CheckPaymentMethod' => \App\Http\Middleware\CheckPaymentMethod::class,
        'checkDebitOrderDetails' => \App\Http\Middleware\CheckDebitOrderDetails::class,
        'checkForCellVerification' => \App\Http\Middleware\CheckMobileVerification::class,
        // Roles and permissions
        'role' => \App\Http\Middleware\VerifyRole::class,
        'permission' => \App\Http\Middleware\VerifyPermission::class,
        'level' => \App\Http\Middleware\VerifyLevel::class,
        'subscribed' => \App\Http\Middleware\Subscribed::class,
        'redirect.subscribed' => \App\Http\Middleware\RedirectIfSubscribed::class,
        'cors' => \Barryvdh\Cors\HandleCors::class,
        'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class
    ];
}
