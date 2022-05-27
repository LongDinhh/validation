<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules\Behaviours;

/**
 * Trait CanConvertValuesToBooleans
 *
 * @package    Coccoc\Validation\Rules\Behaviours
 * @subpackage Coccoc\Validation\Rules\Behaviours\CanConvertValuesToBooleans
 */
trait CanConvertValuesToBooleans
{
    /**
     * @param array $values
     * @return array
     */
    private function convertStringsToBoolean(array $values): array
    {
        return array_map(function ($value) {
            if ($value === 'true') {
                return true;
            } elseif ($value === 'false') {
                return false;
            }

            return $value;
        }, $values);
    }

    /**
     * @param array $values
     * @return array
     */
    private function convertBooleansToString(array $values): array
    {
        return array_map(function ($value) {
            if ($value === true) {
                return 'true';
            } elseif ($value === false) {
                return 'false';
            }

            return (string)$value;
        }, $values);
    }
}
