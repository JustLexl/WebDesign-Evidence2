<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Throwable;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data) 
    { 
        $data['confirmation_code'] = '12345'; 
        $user = User::create([ 
            'name' => $data['name'], 
            'email' => $data['email'], 
            'password' => Hash::make($data['password']), 
            'confirmation_code' => $data['confirmation_code'],
            'is_active' => true,
            'confirmed' => true,
        ]); 

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (Throwable $e) {
        }

        return $user; 
    } 
}
