<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptchaV3 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha_v3.secret_key'),
            'response' => $value,
        ]);
        return $response->json()['success'] ?? false;
    }

    public function message()
    {
        //Google reCAPTCHA verification failed. Please try again.
        return 'សូមសាកល្បង Login ម្តងទៀត';
    }
}
