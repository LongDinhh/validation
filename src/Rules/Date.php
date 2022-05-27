<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Date
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Date
 */
class Date extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.date';

    /**
     * @var array
     */
    protected $fillableParams = ['format'];

    /**
     * @var array
     */
    protected $params = [
        'format' => 'Y-m-d',
    ];

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters($this->fillableParams);

        $format = $this->parameter('format');

        return date_create_from_format($format, (string)$value) !== false;
    }
}
