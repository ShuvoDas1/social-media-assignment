<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserService
{
    public function __construct()
    {

    }

    public function getUserByField(string $fieldName, string $value)
    {
        $user = User::where($fieldName, $value)->first();
        return $user;
    }
}
