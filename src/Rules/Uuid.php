<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class UuidRule
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\UuidRule
 */
class Uuid extends Rule
{
    /**
     * Regular expression pattern for matching a UUID of any variant.
     *
     * Taken from Ramsey\Uuid\Validator\GenericValidator
     */
    private const VALID_PATTERN = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}\z';
    private const NIL = '00000000-0000-0000-0000-000000000000';


    /**
     * @var string
     */
    protected $message = 'rule.uuid';

    /**
     * @var bool
     */
    protected $implicit = true;

    public function check($value): bool
    {
        return !is_null($value) && $this->validate($value) && $value !== self::NIL;
    }

    private function validate(string $uuid): bool
    {
        $uuid = str_replace(['urn:', 'uuid:', 'URN:', 'UUID:', '{', '}'], '', $uuid);

        return $uuid === self::NIL || preg_match('/' . self::VALID_PATTERN . '/Dms', $uuid);
    }
}
