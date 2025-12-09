<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'fullname' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique(User::class),
            ],
            'email' => [ //default email
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            //'password' => $this->passwordRules(),
            'password' => ['required', 'string', 'same:confirm_password', 'min:8'],
            'confirm_password' => 'required',
        ])->validate();

        return User::create([
            'k_fullname' => $input['fullname'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'banned' => 0,
//            'company_id' => 0,
//            'k_category_id' => 0,
//            'k_team'=> 0,
//            'k_province' => 0,
//            'k_parents' => 0
        ]);
    }
}
