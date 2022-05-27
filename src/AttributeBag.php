<?php declare(strict_types=1);

namespace Coccoc\Validation;

use InvalidArgumentException;

/**
 * Class AttributeBag
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\AttributeBag
 *
 * @property array<string, Attribute> $data
 */
class AttributeBag extends DataBag
{
    /**
     * @param string $key
     * @param Attribute $attribute
     * @return $this
     */
    public function add(string $key, Attribute $attribute): self
    {
        $this->set($key, $attribute);

        return $this;
    }

    /**
     *
     */
    public function beforeValidate(): void
    {
        $this->each(function (Attribute $a) {
            return $a->rules()->beforeValidate();
        });
    }

    /**
     * @param string $key
     * @param $value
     * @return AttributeBag
     */
    public function set(string $key, $value)
    {
        if (!$value instanceof Attribute) {
            throw new InvalidArgumentException(sprintf('Value must be an instance of %s', Attribute::class));
        }

        return parent::set($key, $value);
    }
}
