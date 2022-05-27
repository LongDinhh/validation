<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

/**
 * Class RequiredUnless
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\RequiredUnless
 */
class RequiredUnless extends Required
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.required_unless';

    public function fillParameters(array $params): self
    {
        $this->params['field'] = array_shift($params);
        $this->params['values'] = $params;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['field', 'values']);

        $anotherAttribute = $this->parameter('field');
        $definedValues = $this->parameter('values');
        $anotherValue = $this->attribute()->value($anotherAttribute);
        $requiredValidator = $this->validation->factory()->rule('required');

        if (!in_array($anotherValue, $definedValues)) {
            $this->setAttributeAsRequired();

            return $requiredValidator->check($value);
        }

        return true;
    }
}
