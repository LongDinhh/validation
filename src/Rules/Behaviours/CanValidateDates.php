<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules\Behaviours;

use Coccoc\Validation\Exceptions\ParameterException;

/**
 * Trait CanValidateDates
 *
 * @package    Coccoc\Validation\Rules\Behaviours
 * @subpackage Coccoc\Validation\Rules\Behaviours\CanValidateDates
 */
trait CanValidateDates
{
    /**
     * @param string $date
     * @throws ParameterException
     */
    protected function assertDate(string $date): void
    {
        if (!$this->isValidDate($date)) {
            throw ParameterException::invalidDate($date);
        }
    }

    /**
     * @param string $date
     * @return bool
     */
    protected function isValidDate(string $date): bool
    {
        return (strtotime($date) !== false);
    }

    /**
     * @param $date
     * @return int
     */
    protected function getTimeStamp($date): int
    {
        return strtotime($date);
    }
}
