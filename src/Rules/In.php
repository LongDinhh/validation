<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Helper;
use Coccoc\Validation\Rule;

/**
 * Class In
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\In
 */
class In extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.in';

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
        return sprintf('in:%s', Helper::flattenValues($values));
    }

    /**
     * @param array $params
     * @return $this
     */
    public function fillParameters(array $params): self
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

        return in_array($value, $this->parameter('allowed_values'), $this->strict);
    }
}
