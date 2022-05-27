<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanObtainSizeValue;

/**
 * Class Max
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Max
 */
class Max extends Rule
{
    use CanObtainSizeValue;


    /**
     * @var string
     */
    protected $message = 'rule.max';

    /**
     * @var array
     */
    protected $fillableParams = ['max'];

    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $max = $this->getSizeInBytes($this->parameter('max'));
        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        return $valueSize <= $max;
    }
}
