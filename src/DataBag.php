<?php declare(strict_types=1);

namespace Coccoc\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class DataBag
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\DataBag
 */
class DataBag implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $data;

    /**
     * DataBag constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value): bool
    {
        return in_array($value, $this->data, true);
    }

    /**
     * @param ...$value
     * @return bool
     */
    public function containsAnyOf(...$value): bool
    {
        foreach ($value as $test) {
            if ($this->contains($test)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->data as $key => $value) {
            if (false === $callback($value, $key)) {
                break;
            }
        }

        return $this;
    }

    /**
     * @param $value
     * @return bool
     */
    public function excludes($value): bool
    {
        return !$this->contains($value);
    }

    /**
     * @param callable|null $callback
     * @return $this
     */
    public function filter(?callable $callback): self
    {
        return new self(array_filter($this->data, $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @return false|mixed
     */
    public function first()
    {
        return reset($this->data);
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return array|mixed
     */
    public function get(?string $key, $default = null)
    {
        return Helper::arrayGet($this->data, $key, $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return Helper::arrayHas($this->data, $key);
    }

    /**
     * @param string ...$key
     * @return bool
     */
    public function hasAnyOf(string ...$key): bool
    {
        foreach ($key as $test) {
            if ($this->has($test)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return $this
     */
    public function keys(): self
    {
        return new self(array_keys($this->data));
    }

    /**
     * @return false|mixed
     */
    public function last()
    {
        return end($this->data);
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function map(callable $callable): self
    {
        $keys = array_keys($this->data);
        $items = array_map($callable, $this->data, $keys);

        return new self(array_combine($keys, $items));
    }

    /**
     * @param array $params
     * @return $this
     */
    public function merge(array $params)
    {
        $this->data = array_merge($this->data, $params);

        return $this;
    }

    /**
     * @param string ...$key
     * @return array
     */
    public function only(string ...$key): array
    {
        $ret = [];

        foreach ($key as $k) {
            $ret[$k] = $this->get($k);
        }

        return $ret;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function unset(string $key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * @return $this
     */
    public function values(): self
    {
        return new self(array_values($this->data));
    }
}
