<?php declare(strict_types=1);

namespace Coccoc\Validation;

/**
 * Class ErrorMessage
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\ErrorMessage
 */
class ErrorMessage
{
    /**
     * @var null
     */
    private $message = null;

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $params;

    /**
     * ErrorMessage constructor.
     * @param string $key
     * @param array $params
     */
    public function __construct(string $key, array $params = [])
    {
        $this->key = $key;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return strtr($this->message ?? $this->key, $this->toArrayOfStrings($this->params));
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function addParam(string $key, $value): self
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params): self
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $params
     * @return array
     */
    private function toArrayOfStrings(array $params): array
    {
        $ret = [];

        foreach ($params as $key => $value) {
            $prefix = (strpos($key, '[') === 0 || strpos($key, '{') === 0) ? '' : ':';
            $ret[$prefix . $key] = $this->stringify($value);
        }

        return $ret;
    }

    /**
     * @param $value
     * @return string
     */
    private function stringify($value): string
    {
        if (is_string($value) || is_numeric($value)) {
            return (string)$value;
        } elseif (is_array($value) && $this->arrayIsList($value)) {
            return Helper::join(Helper::wraps($value, '"'), ', ', ', ');
        } elseif (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return '';
    }

    /**
     * @param array $array
     * @return bool
     */
    private function arrayIsList(array $array): bool
    {
        if (!function_exists('array_is_list')) {
            $i = 0;
            foreach ($array as $k => $v) {
                if ($k !== $i++) {
                    return false;
                }
            }

            return true;
        }

        return array_is_list($array);
    }
}
