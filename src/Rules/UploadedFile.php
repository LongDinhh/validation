<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules;

use Coccoc\Validation\Contracts\MimeTypeGuesser as MimeTypeGuesserContract;
use Coccoc\Validation\MimeTypeGuesser;
use Coccoc\Validation\Rule;
use Coccoc\Validation\Rules\Behaviours\CanObtainSizeValue;
use Coccoc\Validation\Rules\Behaviours\CanValidateFiles;
use Coccoc\Validation\Rules\Contracts\BeforeValidate;

/**
 * Class UploadedFile
 *
 * @package    Coccoc\Validation\Rules
 * @subpackage Coccoc\Validation\Rules\UploadedFile
 */
class UploadedFile extends Rule implements BeforeValidate
{
    use CanValidateFiles;
    use CanObtainSizeValue;

    /**
     * @var string
     */
    protected $message = 'rule.uploaded_file';

    /**
     * @var MimeTypeGuesserContract|MimeTypeGuesser
     */
    protected $guesser;

    /**
     * UploadedFile constructor.
     * @param MimeTypeGuesserContract|null $guesser
     */
    public function __construct(MimeTypeGuesserContract $guesser = null)
    {
        $this->guesser = $guesser ?? new MimeTypeGuesser();
    }

    /**
     * @param array $params
     * @return $this
     */
    public function fillParameters(array $params): self
    {
        if (count($params) < 2) {
            return $this;
        }

        $this->minSize(array_shift($params));
        $this->maxSize(array_shift($params));
        $this->types($params);

        return $this;
    }

    /**
     * Set the minimum filesize
     *
     * @param int|string $size
     * @return $this
     */
    public function minSize($size): self
    {
        $this->params['min_size'] = $size;

        return $this;
    }

    /**
     * Set the max allowed file size
     *
     * @param int|string $size
     * @return $this
     */
    public function maxSize($size): self
    {
        $this->params['max_size'] = $size;

        return $this;
    }

    /**
     * Set the filesize between the min/max
     *
     * @param int|string $min
     * @param int|string $max
     * @return $this
     */
    public function between($min, $max): self
    {
        $this->minSize($min);
        $this->maxSize($max);

        return $this;
    }

    /**
     * Set the array of allowed types e.g. doc,docx,xls,xlsx
     */
    public function types($types): self
    {
        if (is_string($types)) {
            $types = explode(',', $types);
        }

        $this->params['allowed_types'] = $types;

        return $this;
    }

    public function beforeValidate(): void
    {
        $attribute = $this->attribute();

        // We only resolve uploaded file value
        // from complex attribute such as 'files.photo', 'images.*', 'images.foo.bar', etc.
        if (!$attribute->isUsingDotNotation()) {
            return;
        }

        $keys = explode(".", $attribute->key());
        $firstKey = array_shift($keys);
        $firstKeyValue = $this->validation->input()->get($firstKey);

        $resolvedValue = $this->resolveUploadedFileValue($firstKeyValue);

        // Return original value if $value can't be resolved as uploaded file value
        if (!$resolvedValue) {
            return;
        }

        $this->validation->input()->set($firstKey, $resolvedValue);
    }

    /**
     * @param $value
     * @return bool
     */
    public function check($value): bool
    {
        $minSize = $this->parameter('min_size');
        $maxSize = $this->parameter('max_size');
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

        if ($minSize && $value['size'] < $this->getSizeInBytes($minSize)) {
            $this->message = 'rule.uploaded_file.min_size';

            return false;
        }

        if ($maxSize && $value['size'] > $this->getSizeInBytes($maxSize)) {
            $this->message = 'rule.uploaded_file.max_size';

            return false;
        }

        if (!empty($allowedTypes) && !in_array($this->guesser->getExtension($value['type']), $allowedTypes)) {
            $this->message = 'rule.uploaded_file.type';

            return false;
        }

        return true;
    }
}
