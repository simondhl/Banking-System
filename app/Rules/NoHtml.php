<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoHtml implements Rule
{

    public function passes($attribute, $value)
    {

        if (strip_tags($value) !== $value) {
            return false;
        }

        if (preg_match('/javascript:|on\w+\s*=/i', $value)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'It is not allowed to write HTML and JavaScript codes in this feild';
    }
}
