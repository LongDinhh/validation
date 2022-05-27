<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules\Contracts;

/**
 * Interface BeforeValidate
 *
 * @package    Coccoc\Validation\Rules\Contracts
 * @subpackage Coccoc\Validation\Rules\Contracts\BeforeValidate
 */
interface BeforeValidate
{
    public function beforeValidate(): void;
}
