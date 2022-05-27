<?php declare(strict_types=1);

namespace Coccoc\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class ErrorBag
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\ErrorBag
 */
class ErrorBag implements Countable, IteratorAggregate
{
    /**
     * @var $errors
     */
    public $errors;

    /**
     * ErrorBag constructor.
     * @param array $errors
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $key => $rules) {
            foreach ($rules as $rule => $error) {
                $this->add($key, $rule, $error);
            }
        }
    }

    /**
     * @param string $key
     * @param string $rule
     * @param ErrorMessage $message
     */
    public function add(string $key, string $rule, ErrorMessage $message): void
    {
        $this->errors[$key][$rule] = $message;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->all());
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @param string $format
     * @return array
     */
    public function all(string $format = ':message'): array
    {
        $results = [];

        foreach ($this->errors as $keyMessages) {
            foreach ($keyMessages as $message) {
                $results[] = $this->formatMessage($message, $format);
            }
        }

        return $results;
    }

    /**
     * @param ErrorMessage $message
     * @param string $format
     * @return string
     */
    private function formatMessage(ErrorMessage $message, string $format): string
    {
        return str_replace(':message', (string)$message, $format);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        [$key, $ruleName] = $this->parsekey($key);

        if ($this->isWildcardKey($key)) {
            $messages = $this->filterMessagesForWildcardKey($key, $ruleName);

            return count(Helper::arrayDot($messages)) > 0;
        } else {
            $messages = $this->errors[$key] ?? null;

            if (!$ruleName) {
                return !empty($messages);
            } else {
                return !empty($messages) && isset($messages[$ruleName]);
            }
        }
    }

    /**
     * @param string $key
     * @return array
     */
    private function parseKey(string $key): array
    {
        $expl = explode(':', $key, 2);
        $key = $expl[0];
        $ruleName = $expl[1] ?? null;

        return [$key, $ruleName];
    }

    /**
     * @param string $key
     * @return bool
     */
    private function isWildcardKey(string $key): bool
    {
        return strpos($key, '*') !== false;
    }

    /**
     * @param string $key
     * @param null $ruleName
     * @return array
     */
    private function filterMessagesForWildcardKey(string $key, $ruleName = null): array
    {
        $messages = $this->errors;
        $pattern = preg_quote($key, '#');
        $pattern = str_replace('\*', '.*', $pattern);

        $filteredMessages = [];

        foreach ($messages as $k => $keyMessages) {
            if ((bool)preg_match('#^' . $pattern . '\z#u', $k) === false) {
                continue;
            }

            foreach ($keyMessages as $rule => $message) {
                if ($ruleName && $rule != $ruleName) {
                    continue;
                }
                $filteredMessages[$k][$rule] = $message;
            }
        }

        return $filteredMessages;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function first(string $key): ?string
    {
        [$key, $ruleName] = $this->parsekey($key);

        if ($this->isWildcardKey($key)) {
            $messages = $this->filterMessagesForWildcardKey($key, $ruleName);
            $flattenMessages = Helper::arrayDot($messages);

            $ret = array_shift($flattenMessages);
        } else {
            $keyMessages = $this->errors[$key] ?? [];

            if (empty($keyMessages)) {
                return null;
            }

            if ($ruleName) {
                $ret = $keyMessages[$ruleName] ?? null;
            } else {
                $ret = array_shift($keyMessages);
            }
        }

        return !is_null($ret) ? (string)$ret : null;
    }

    /**
     * @param string $key
     * @param string $format
     * @return array
     */
    public function get(string $key, string $format = ':message'): array
    {
        [$key, $ruleName] = $this->parsekey($key);

        $results = [];

        if ($this->isWildcardKey($key)) {
            $messages = $this->filterMessagesForWildcardKey($key, $ruleName);

            foreach ($messages as $explicitKey => $keyMessages) {
                foreach ($keyMessages as $rule => $message) {
                    $results[$explicitKey][$rule] = $this->formatMessage($message, $format);
                }
            }
        } else {
            $keyMessages = $this->errors[$key] ?? [];

            foreach ($keyMessages as $rule => $message) {
                if ($ruleName && $ruleName != $rule) {
                    continue;
                }

                $results[$rule] = $this->formatMessage($message, $format);
            }
        }

        return $results;
    }

    /**
     * @param string $format
     * @param bool $dotNotation
     * @return array
     */
    public function firstOfAll(string $format = ':message', bool $dotNotation = false): array
    {
        $messages = $this->errors;
        $results = [];

        foreach ($messages as $key => $keyMessages) {
            if ($dotNotation) {
                $results[$key] = $this->formatMessage(array_shift($messages[$key]), $format);
            } else {
                Helper::arraySet($results, $key, $this->formatMessage(array_shift($messages[$key]), $format));
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->errors;
    }

    /**
     * @return DataBag
     */
    public function toDataBag(): DataBag
    {
        return new DataBag($this->errors);
    }
}
