<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GmailValidation implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domainPart = explode('@', $value)[1]?? null;

        if (!$domainPart || strtolower($domainPart)!== 'gmail.com') {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a Gmail address.';
    }
}
