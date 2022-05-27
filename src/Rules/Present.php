<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Present
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Present
 */
class Present extends Rule
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.present';

    public function check($value): bool
    {
        $this->setAttributeAsRequired();

        return $this->validation->input()->has($this->attribute->key());
    }

    protected function setAttributeAsRequired()
    {
        if ($this->attribute) {
            $this->attribute->makeRequired();
        }
    }
}
