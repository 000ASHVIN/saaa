<?php

namespace App\Http\ViewComposers;

use App\Users\User;
use Illuminate\Contracts\View\View;

class UserCountComposer
{
    protected $users;

    public function __construct()
    {
        $this->users = $users = User::where('deleted_at', null)->count();
    }

    public function compose(View $view)
    {
        $view->with('users', $this->users);
    }
} 