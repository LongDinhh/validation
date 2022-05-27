<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Regex
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Regex
 */
class Regex extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.regex';

    /**
     * @var array
     */
    protected $fillableParams = ['regex'];

    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        return preg_match($this->parameter('regex'), $value) > 0;
    }
}
