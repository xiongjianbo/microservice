<?php

namespace app\auth\validate;

use think\Validate;

class Auth extends Validate
{
    protected $rule = [
        'username' => 'require|max:25',
        'password' => 'require|alphaDash|max:30',
    ];

    protected $message = [
    ];
}
