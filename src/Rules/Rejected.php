<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Rejected
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Rejected
 */
class Rejected extends Rule
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.rejected';

    /**
     * @var array
     */
    protected $params = ['rejected' => ['no', 'off', '0', 0, false, 'false']];

    public function check($value): bool
    {
        return in_array($value, $this->params['rejected'], true);
    }
}
