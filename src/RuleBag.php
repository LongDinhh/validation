<?php declare(strict_types=1);

namespace Coccoc\Validation;

use InvalidArgumentException;
use Coccoc\Validation\Rules\Contracts\BeforeValidate;

/**
 * Class RuleBag
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\RuleBag
 *
 * @property array<int, Rule>
 */
class RuleBag extends DataBag
{
    /**
     * @var Attribute $attribute
     */
    private $attribute;

    /**
     * RuleBag constructor.
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(Attribute $attribute, array $data = [])
    {
        parent::__construct();

        $this->attribute = $attribute;

        foreach ($data as $rule) {
            $this->add($rule);
        }
    }

    public function add(Rule $rule): void
    {
        $this->set($rule->name(), $rule);
    }

    /**
     * @param string $key
     * @param $value
     * @return RuleBag
     */
    public function set(string $key, $value)
    {
        if (!$value instanceof Rule) {
            throw new InvalidArgumentException('Value must be an instance of ' . Rule::class);
        }

        $value->setAttribute($this->attribute);
        $value->setValidation($this->attribute->validation());

        return parent::set($key, $value);
    }

    /**
     * @return $this
     */
    public function beforeValidate(): self
    {
        $this
            ->filter(function (Rule $r) {
                return $r instanceof BeforeValidate;
            })
            ->each(function (BeforeValidate $r) {
                $r->beforeValidate();
            });

        return $this;
    }
}
