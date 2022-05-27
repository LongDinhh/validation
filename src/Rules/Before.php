<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanValidateDates;

/**
 * Class Before
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Before
 */
class Before extends Rule
{
    use CanValidateDates;

    /**
     * @var string
     */
    protected $message = 'rule.before';

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

        return $this->getTimeStamp($time) > $this->getTimeStamp($value);
    }
}
