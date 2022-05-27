<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Helper;
use Coccoc\Validation\Rule;

/**
 * Class AnyOf
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\AnyOf
 */
class AnyOf extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.any_of';

    /**
     * @var bool
     */
    protected $strict = false;

    /**
     * @param array $values
     * @return string
     */
    public static function make(array $values): string
    {
        return sprintf('any_of:%s', Helper::flattenValues($values));
    }

    /**
     * @param array $params
     * @return $this
     */
    public function fillParameters(array $params)
    {
        if (count($params) == 1 && is_array($params[0])) {
            $params = $params[0];
        }

        $this->params['allowed_values'] = $params;

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function values(array $values): self
    {
        $this->params['allowed_values'] = $values;

        return $this;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function separator(string $char): self
    {
        $this->params['separator'] = $char;

        return $this;
    }

    /**
     * @param bool $strict
     * @return $this
     */
    public function strict(bool $strict = true): self
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['allowed_values']);

        $valid  = true;
        $values = is_string($value) ? explode($this->parameter('separator', ','), $value) : (array)$value;

        foreach ($values as $v) {
            $valid = $valid && in_array($v, $this->parameter('allowed_values'), $this->strict);
        }

        return $valid;
    }
}
