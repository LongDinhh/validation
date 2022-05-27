<?php declare(strict_types=1);

namespace Coccoc\Validation\Exceptions;

use Exception;
use Coccoc\Validation\Rule;

/**
 * Class RuleException
 *
 * @package    Coccoc\Validation\Exceptions
 * @subpackage Coccoc\Validation\Exceptions\RuleException
 */
class RuleException extends Exception
{
    /**
     * @param string $rule
     * @return static
     */
    public static function notFound(string $rule): self
    {
        return new self(sprintf('Validator "%s" is not registered', $rule));
    }

    /**
     * @param string $rule
     * @return static
     */
    public static function invalidRuleType(string $rule): self
    {
        return new self(sprintf('Rule must be a string, Closure or "%s" instance; "%s" given', Rule::class, $rule));
    }
}
