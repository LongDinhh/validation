<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanConvertValuesToBooleans;

/**
 * Class ProhibitedUnlessRule
 *
 * Based on Laravel validators prohibited_unless
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\ProhibitedUnlessRule
 */
class ProhibitedUnless extends Rule
{
    use CanConvertValuesToBooleans;

    /**
     * @var string
     */
    protected $message = 'rule.prohibited_unless';

    /**
     * @var bool
     */
    protected $implicit = true;

    public function fillParameters(array $params): Rule
    {
        $this->params['field'] = array_shift($params);
        $this->params['values'] = $this->convertStringsToBoolean($params);

        return $this;
    }

    public function field(string $field): self
    {
        $this->params['field'] = $field;

        return $this;
    }

    public function values(array $values): self
    {
        $this->params['values'] = $values;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['field', 'values']);

        $anotherAttribute = $this->parameter('field');
        $definedValues = $this->parameter('values');
        $anotherValue = $this->attribute()->value($anotherAttribute);

        $requiredValidator = $this->validation->factory()->rule('required');

        if (!in_array($anotherValue, $definedValues, is_bool($anotherValue))) {
            return !$requiredValidator->check($value);
        }

        return true;
    }

    protected function convertParametersForMessage(): array
    {
        return array_merge($this->params, ['values' => $this->convertBooleansToString($this->params['values'])]);
    }
}
