<?php declare(strict_types=1);

namespace Coccoc\Validation;

use Coccoc\Validation\Exceptions\ParameterException;

/**
 * Class Rule
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\Rule
 */
abstract class Rule
{
    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @var Attribute|null
     */
    protected $attribute = null;

    /**
     * @var Validation|null
     */
    protected $validation = null;

    /**
     * @var bool
     */
    protected $implicit = false;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $fillableParams = [];

    /**
     * @var string
     */
    protected $message = 'rule.default';

    /**
     * @param $value
     * @return bool
     */
    abstract public function check($value): bool;

    /**
     * @return Attribute|null
     */
    public function attribute(): ?Attribute
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    /**
     * @param Validation $validation
     */
    public function setValidation(Validation $validation): void
    {
        $this->validation = $validation;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function fillParameters(array $params)
    {
        foreach ($this->fillableParams as $key) {
            if (empty($params)) {
                break;
            }

            $this->params[$key] = array_shift($params);
        }

        return $this;
    }

    /**
     * Get parameter from given $key, return $default if it does not exist (null)
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function parameter(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * @return bool
     */
    public function isImplicit(): bool
    {
        return $this->implicit;
    }

    /**
     * @return $this
     */
    public function makeImplicit(): self
    {
        $this->implicit = true;

        return $this;
    }

    /**
     * @param array $params
     * @return ErrorMessage
     */
    public function message(array $params = []): ErrorMessage
    {
        $params = array_merge(
            [
                'attribute' => $this->attribute->alias() ?? $this->attribute->key(),
                'value' => $this->attribute->value()
            ],
            $this->convertParametersForMessage(),
            $params
        );

        return new ErrorMessage($this->message, $params);
    }

    /**
     * @return array
     */
    protected function convertParametersForMessage(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @throws ParameterException
     */
    protected function assertHasRequiredParameters(array $params): void
    {
        foreach ($params as $param) {
            if (!isset($this->params[$param])) {
                throw ParameterException::missing($this->name(), $param);
            }
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name ?: get_class($this);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
