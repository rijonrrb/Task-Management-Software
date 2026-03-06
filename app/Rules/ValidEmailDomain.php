<?php

namespace App\Rules;

use App\Services\SecurityService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!SecurityService::isEmailDomainValid($value)) {
            $fail('The email domain is not accepted. Please use a valid email provider.');
        }
    }
}
