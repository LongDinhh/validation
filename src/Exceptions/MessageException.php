<?php declare(strict_types=1);

namespace Coccoc\Validation\Exceptions;

use Exception;

/**
 * Class MessageException
 *
 * @package    Coccoc\Validation\Exceptions
 * @subpackage Coccoc\Validation\Exceptions\MessageException
 */
class MessageException extends Exception
{
    /**
     * @param string $lang
     * @param string $key
     * @return static
     */
    public static function noMessageForKey(string $lang, string $key): self
    {
        return new self(sprintf('No message was found for the language "%s" and "%s"', $lang, $key));
    }

    /**
     * @param string $lang
     * @param array $keys
     * @return static
     */
    public static function noMessageForKeys(string $lang, array $keys): self
    {
        return new self(sprintf('No message was found for the language "%s" and any of: "%s"', $lang, implode('","', $keys)));
    }
}
