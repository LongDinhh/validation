<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanValidateFiles;

/**
 * Class Required
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Required
 */
class Required extends Rule
{
    use CanValidateFiles;

    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var string
     */
    protected $message = 'rule.required';

    public function check($value): bool
    {
        $this->setAttributeAsRequired();

        if ($this->attribute && $this->attribute->rules()->has('uploaded_file')) {
            return $this->isValueFromUploadedFiles($value) && $value['error'] != UPLOAD_ERR_NO_FILE;
        }

        if (is_string($value)) {
            return mb_strlen(trim($value), 'UTF-8') > 0;
        }
        if (is_array($value)) {
            return count($value) > 0;
        }

        return !is_null($value);
    }

    protected function setAttributeAsRequired(): void
    {
        if ($this->attribute) {
            $this->attribute->makeRequired();
        }
    }
}
