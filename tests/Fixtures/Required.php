<?php

namespace Coccoc\Validation\Tests;

use Coccoc\Validation\Rule;

class Required extends Rule
{

    public function check($value): bool
    {
        return true;
    }
}
