<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;


    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'store_name' => ['nullable', 'string', 'max:255'],
            'categories_id' => ['nullable', 'integer'],
            'is_store_open' => ['required'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'store_name' => isset($input['store_name']) ? $input['store_name'] : '',
            'categories_id' => isset($input['categories_id']) ? $input['categories_id'] : NULL,
            'store_status' => isset($input['is_store_open']) ? 1 : 0,
        ]);
    }
}
