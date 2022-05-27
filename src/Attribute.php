<?php declare(strict_types=1);

namespace Coccoc\Validation;

/**
 * Class Attribute
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\Attribute
 */
class Attribute
{
    /**
     * @var Validation $validation
     */
    private $validation;

    /**
     * @var Attribute|null $parent
     */
    private $parent = null;

    /**
     * @var RuleBag $rules
     */
    private $rules;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * @var bool $required
     */
    private $required = false;

    /**
     * @var array
     */
    private $indexes = [];

    /**
     * Attribute constructor.
     * @param Validation $validation
     * @param string $key
     * @param string|null $alias
     * @param array $rules
     */
    public function __construct(
        Validation $validation,
        string $key,
        string $alias = null,
        array $rules = []
    ) {
        $this->validation = $validation;
        $this->alias = $alias;
        $this->key = $key;
        $this->rules = new RuleBag($this, $rules);
    }

    public function makeRequired(): void
    {
        $this->required = true;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isArrayAttribute(): bool
    {
        return count($this->indexes()) > 0;
    }

    /**
     * @return string|null
     */
    public function alias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return array
     */
    public function indexes(): array
    {
        return $this->indexes;
    }

    /**
     * @param array $indexes
     */
    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }

    /**
     * @param string $key
     * @return string
     */
    private function resolveSiblingKey(string $key): string
    {
        $indexes = $this->indexes();
        $keys = explode("*", $key);
        $countAsterisks = count($keys) - 1;

        if (count($indexes) < $countAsterisks) {
            $indexes = array_merge($indexes, array_fill(0, $countAsterisks - count($indexes), "*"));
        }

        $args = array_merge([str_replace("*", "%s", $key)], $indexes);

        return call_user_func_array('sprintf', $args);
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * @return bool
     */
    public function isUsingDotNotation(): bool
    {
        return strpos($this->key(), '.') === 0;
    }

    /**
     * @return Attribute|null
     */
    public function parent(): ?Attribute
    {
        return $this->parent;
    }

    /**
     * @param Attribute $parent
     */
    public function setParent(Attribute $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return RuleBag
     */
    public function rules(): RuleBag
    {
        return $this->rules;
    }

    /**
     * @return Validation
     */
    public function validation(): Validation
    {
        return $this->validation;
    }

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public function value(string $key = null)
    {
        if ($key && $this->isArrayAttribute()) {
            $key = $this->resolveSiblingKey($key);
        }

        if (!$key) {
            $key = $this->key();
        }

        return $this->validation->input()->get($key);
    }
}
