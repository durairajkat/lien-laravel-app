<?php

namespace App\Services\Auth;

use App\User;
use App\Jobs\SendInvitationOnRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    /**
     * Register a new user with related data
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            // Create User
            $user = User::create([
                'email'    => $data['email'],
                'user_name'=> $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 5, // member
                'status'   => '0', // active
            ]);
           
            // Dispatch invitation / activation mail
            SendInvitationOnRegister::dispatch($user);

            return $user;
        }, 3);
    }
}
