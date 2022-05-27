<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Accepted
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Accepted
 */
class Accepted extends Rule
{

    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.accepted';

    /**
     * @var array
     */
    protected $params = ['accepted' => ['yes', 'on', '1', 1, true, 'true']];

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        return in_array($value, $this->params['accepted'], true);
    }
}
