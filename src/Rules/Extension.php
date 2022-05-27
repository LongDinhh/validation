<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;

/**
 * Class Extension
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Extension
 */
class Extension extends Rule
{
    /**
     * @var string
     */
    protected $message = 'rule.extension';

    /**
     * @param array $params
     * @return $this
     */
    public function fillParameters(array $params): self
    {
        if (count($params) == 1 && is_array($params[0])) {
            $params = $params[0];
        }
        $this->params['allowed_extensions'] = $params;

        return $this;
    }

    /**
     * @param $value
     * @return bool
     * @throws \Coccoc\Validation\Exceptions\ParameterException
     */
    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['allowed_extensions']);

        $allowedExtensions = $this->parameter('allowed_extensions');

        foreach ($allowedExtensions as $key => $ext) {
            $allowedExtensions[$key] = ltrim($ext, '.');
        }

        $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        return $ext && in_array($ext, $allowedExtensions);
    }
}
