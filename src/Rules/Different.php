<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Different
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Different
 */
class Different extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.different';

    /**
     * @var array
     */
    protected $fillableParams = ['field'];

    /**
     * @param string $field
     * @return $this
     */
    public function field(string $field): self
    {
        $this->params['field'] = $field;

        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $field = $this->parameter('field');
        $anotherValue = $this->validation->input()->get($field);

        return $value != $anotherValue;
    }
}
