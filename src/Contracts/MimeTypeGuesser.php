<?php declare(strict_types=1);

namespace Coccoc\Validation\Contracts;

/**
 * Class MimeTypeGuesser
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\MimeTypeGuesser
 */
interface MimeTypeGuesser
{
    /**
     * @param string $mimeType
     * @return string|null
     */
    public function getExtension(string $mimeType): ?string;

    /**
     * @param string $extension
     * @return string
     */
    public function getMimeType(string $extension): string;
}
