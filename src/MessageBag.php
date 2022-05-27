<?php declare(strict_types=1);

namespace Coccoc\Validation;

use Coccoc\Validation\Exceptions\MessageException;

/**
 * Class MessageBag
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\MessageBag
 */
class MessageBag
{

    /**
     * @var array
     */
    private $messages;

    /**
     * @var string
     */
    private $defaultLang = 'en';

    /**
     * MessageBag constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $lang
     * @return $this
     */
    public function default(string $lang): self
    {
        $this->defaultLang = $lang;

        return $this;
    }

    /**
     * @param string $lang
     * @return array
     */
    public function all(string $lang): array
    {
        return $this->messages[$lang] ?? [];
    }

    /**
     * @param string $lang
     * @param array $messages
     * @return $this
     */
    public function add(string $lang, array $messages): self
    {
        foreach ($messages as $key => $message) {
            $this->replace($lang, $key, $message);
        }

        return $this;
    }

    /**
     * @param string $lang
     * @param string $key
     * @param string $message
     * @return $this
     */
    public function replace(string $lang, string $key, string $message): self
    {
        $this->messages[$lang][$key] = $message;

        return $this;
    }

    /**
     * @param array $keys
     * @param string|null $lang
     * @return string
     * @throws MessageException
     */
    public function firstOf(array $keys, string $lang = null): string
    {
        foreach ($keys as $key) {
            if ($this->has($key, $lang)) {
                return $this->get($key, $lang);
            }
        }

        throw MessageException::noMessageForKeys($lang ?? $this->defaultLang, $keys);
    }

    /**
     * @param string $key
     * @param string|null $lang
     * @return string|null
     */
    public function get(string $key, string $lang = null): ?string
    {
        return $this->messages[$lang ?? $this->defaultLang][$key] ?? null;
    }

    /**
     * @param string $key
     * @param string|null $lang
     * @return bool
     */
    public function has(string $key, string $lang = null): bool
    {
        return isset($this->messages[$lang ?? $this->defaultLang][$key]);
    }
}
