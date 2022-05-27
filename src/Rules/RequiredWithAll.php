<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

/**
 * Class RequiredWithAll
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\RequiredWithAll
 */
class RequiredWithAll extends Required
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.required_with_all';

    public function fillParameters(array $params): self
    {
        $this->params['fields'] = $params;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['fields']);

        $fields = $this->parameter('fields');
        $requiredValidator = $this->validation->factory()->rule('required');

        foreach ($fields as $field) {
            if (!$this->validation->input()->has($field)) {
                return true;
            }
        }

        $this->setAttributeAsRequired();

        return $requiredValidator->check($value);
    }
}
