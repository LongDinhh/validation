<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Contracts\MimeTypeGuesser as MimeTypeGuesserContract;
use Coccoc\Validation\MimeTypeGuesser;
use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanValidateFiles;

/**
 * Class Mimes
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\Mimes
 */
class Mimes extends Rule
{
    use CanValidateFiles;

    /**
     * @var string
     */
    protected $message = 'rule.mimes';

    /**
     * @var MimeTypeGuesserContract|MimeTypeGuesser
     */
    protected $guesser;

    public function __construct(MimeTypeGuesserContract $guesser = null)
    {
        $this->guesser = $guesser ?? new MimeTypeGuesser();
    }

    public function fillParameters(array $params): self
    {
        $this->types($params);

        return $this;
    }

    public function types($types): self
    {
        if (is_string($types)) {
            $types = explode(',', $types);
        }

        $this->params['allowed_types'] = $types;

        return $this;
    }

    public function check($value): bool
    {
        $allowedTypes = $this->parameter('allowed_types');

        // below is Required rule job
        if (!$this->isValueFromUploadedFiles($value) || $value['error'] == UPLOAD_ERR_NO_FILE) {
            return true;
        }

        if (!$this->isUploadedFile($value)) {
            return false;
        }

        // just make sure there is no error
        if ($value['error']) {
            return false;
        }

        if (!empty($allowedTypes) && !in_array($this->guesser->getExtension($value['type']), $allowedTypes)) {
            return false;
        }

        return true;
    }
}
