<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Same
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Same
 */
class Same extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.same';

    /**
     * @var array
     */
    protected $fillableParams = ['field'];

    public function field(string $field): self
    {
        $this->params['field'] = $field;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $field = $this->parameter('field');
        $anotherValue = $this->attribute()->value($field);

        return $value == $anotherValue;
    }
}
