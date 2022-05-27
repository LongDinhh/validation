<?php declare(strict_types=1);

namespace Coccoc\Validation\Rules\Behaviours;

use InvalidArgumentException;

/**
 * Trait CanValidateSize
 *
 * @package    Coccoc\Validation\Rules\Behaviours
 * @subpackage Coccoc\Validation\Rules\Behaviours\CanValidateSize
 */
trait CanObtainSizeValue
{
    /**
     * Get size (int) value from given $value
     */
    protected function getValueSize($value): float
    {
        if ($this->attribute()
            && ($this->attribute()->rules()->hasAnyOf('numeric', 'integer'))
            && is_numeric($value)
        ) {
            $value = (float)$value;
        }

        if (is_int($value) || is_float($value)) {
            return (float)$value;
        } elseif (is_string($value)) {
            return (float)mb_strlen($value, 'UTF-8');
        } elseif ($this->isUploadedFileValue($value)) {
            return (float)$value['size'];
        } elseif (is_array($value)) {
            return (float)count($value);
        } else {
            return 0.0;
        }
    }

    /**
     * Check whether value is from $_FILES
     */
    public function isUploadedFileValue($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        $keys = ['name', 'type', 'tmp_name', 'size', 'error'];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function getSizeInBytes($size): float
    {
        if (is_numeric($size)) {
            return (float)$size;
        }

        if (!is_string($size)) {
            throw new InvalidArgumentException("Size must be string or numeric bytes");
        }

        if (!preg_match("/^(?<number>((\d+)?\.)?\d+)(?<format>([BKMGTP])B?)?$/i", $size, $match)) {
            throw new InvalidArgumentException("Size is not valid format, expected number + B, KB, MB, GB, TB, PB");
        }

        $number = (float)$match['number'];
        $format = $match['format'] ?? '';

        switch (strtoupper($format)) {
            case "KB":
            case "K":
                return $number * 1024;
            case "MB":
            case "M":
                return $number * pow(1024, 2);
            case "GB":
            case "G":
                return $number * pow(1024, 3);
            case "TB":
            case "T":
                return $number * pow(1024, 4);
            case "PB":
            case "P":
                return $number * pow(1024, 5);
            default:
                return $number;
        }
    }
}
