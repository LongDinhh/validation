<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules\Contracts;

/**
 * Interface ModifyValue
 *
 * @package    Coccoc\Validation\Rules\Contracts
 * @subpackage Coccoc\Validation\Rules\Contracts\ModifyValue
 */
interface ModifyValue
{
    /**
     * Modify given value so in current and next rules returned value will be used
     */
    public function modifyValue($value);
}
