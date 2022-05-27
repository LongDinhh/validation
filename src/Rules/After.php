<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanValidateDates;

/**
 * Class After
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\After
 */
class After extends Rule
{
    use CanValidateDates;

    /**
     * @var string
     */
    protected $message = 'rule.after';

    /**
     * @var array
     */
    protected $fillableParams = ['time'];

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $time = $this->parameter('time');

        $this->assertDate($value);
        $this->assertDate($time);

        return $this->getTimeStamp($time) < $this->getTimeStamp($value);
    }
}
