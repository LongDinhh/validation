<?php declare(strict_types=1);

namespace Coccoc\Validation\Exceptions;

use Exception;

/**
 * Class MissingRequiredParameterException
 *
 * @package    Coccoc\Validation\Exceptions
 * @subpackage Coccoc\Validation\Exceptions\MissingRequiredParameterException
 */
class ParameterException extends Exception
{
    /**
     * @param string $rule
     * @param string $param
     * @return static
     */
    public static function missing(string $rule, string $param): self
    {
        return new self(sprintf('Missing required parameter "%s" on rule "%s"', $param, $rule));
    }

    /**
     * @param string $value
     * @return static
     */
    public static function invalidDate(string $value): self
    {
        return new self(sprintf('"%s" is not a valid date format', $value));
    }
}
