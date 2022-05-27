<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Closure;
use InvalidArgumentException;
use Coccoc\Validation\Rule;

/**
 * Class Callback
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Callback
 */
class Callback extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.default';

    /**
     * @var array
     */
    protected $fillableParams = ['callback'];

    /**
     * @param Closure $callback
     * @return $this
     */
    public function through(Closure $callback): self
    {
        $this->params['callback'] = $callback;

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

        $callback = $this->parameter('callback');

        if (!$callback instanceof Closure) {
            throw new InvalidArgumentException(sprintf('Callback rule for "%s" is not callable.', $this->attribute->key()));
        }

        $callback = $callback->bindTo($this);
        $invalidMessage = $callback($value);

        if (is_string($invalidMessage)) {
            $this->message = $invalidMessage;

            return false;
        }

        return $invalidMessage;
    }
}
