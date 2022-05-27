<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Contracts\ModifyValue;

/**
 * Class Defaults
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Defaults
 */
class Defaults extends Rule implements ModifyValue
{
    /**
     * @var string
     */
    protected $message = 'rule.default_value';

    /**
     * @var array
     */
    protected $fillableParams = ['default'];

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        return true;
    }

    /**
     * @param $value
     * @return mixed|null
     */
    public function modifyValue($value)
    {
        return $this->isEmptyValue($value) ? $this->parameter('default') : $value;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isEmptyValue($value): bool
    {
        return false === (new Required)->check($value);
    }
}
