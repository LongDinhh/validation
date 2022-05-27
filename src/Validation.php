<?php declare(strict_types=1);

namespace Coccoc\Validation;

use Closure;
use Coccoc\Validation\Exceptions\RuleException;
use Coccoc\Validation\Rules\Contracts\ModifyValue;
use Coccoc\Validation\Rules\Required;

/**
 * Class Validation
 *
 * @package    Coccoc\Validation
 * @subpackage Coccoc\Validation\Validation
 */
class Validation
{
    /**
     * @var AttributeBag $attributes
     */
    private $attributes;

    /**
     * @var ErrorBag $errors
     */
    private $errors;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var InputBag
     */
    private $input;

    /**
     * @var MessageBag
     */
    private $messages;

    /**
     * @var array
     */
    private $aliases = [];

    /**
     * @var array
     */
    private $validData = [];

    /**
     * @var array
     */
    private $invalidData = [];

    /**
     * @var string
     */
    private $separator = ':';

    /**
     * @var string|null
     */

    /**
     * @var string|null
     */
    private $lang = null;

    /**
     * Validation constructor.
     * @param Factory $factory
     * @param array $inputs
     * @param array $rules
     * @throws RuleException
     */
    public function __construct(Factory $factory, array $inputs, array $rules)
    {
        $this->factory = $factory;
        $this->messages = clone $factory->messages();
        $this->errors = new ErrorBag();
        $this->input = new InputBag($inputs);
        $this->attributes = new AttributeBag();

        foreach ($rules as $attributeKey => $rule) {
            $this->addAttribute($attributeKey, $rule);
        }
    }

    /**
     * @param array $inputs
     */
    public function validate(array $inputs = []): void
    {
        $this->errors = new ErrorBag();
        $this->input->merge($inputs);

        $this->attributes->beforeValidate();

        foreach ($this->attributes as $attribute) {
            $this->validateAttribute($attribute);
        }
    }

    /**
     * @param Attribute $attribute
     */
    protected function validateAttribute(Attribute $attribute): void
    {
        if ($this->isArrayAttribute($attribute)) {
            $attributes = $this->parseArrayAttribute($attribute);

            foreach ($attributes as $attr) {
                $this->validateAttribute($attr);
            }

            return;
        }

        if ($attribute->rules()->has('sometimes') && !$this->input->has($attribute->key())) {
            return;
        }

        $value = $this->input->get($attribute->key());
        $isEmptyValue = $this->isEmptyValue($value);
        $rules = ($attribute->rules()->has('nullable') && $isEmptyValue) ? [] : $attribute->rules();

        $isValid = true;

        foreach ($rules as $ruleValidator) {
            $ruleValidator->setAttribute($attribute);

            if ($ruleValidator instanceof ModifyValue) {
                $value = $ruleValidator->modifyValue($value);
                $isEmptyValue = $this->isEmptyValue($value);
            }

            $valid = $ruleValidator->check($value);

            if ($isEmptyValue && $this->ruleIsOptional($attribute, $ruleValidator)) {
                continue;
            }

            if (!$valid) {
                $isValid = false;
                $this->addError($attribute, $ruleValidator, $value);

                if ($ruleValidator->isImplicit()) {
                    break;
                }
            }
        }

        if ($isValid) {
            $this->setValidData($attribute, $value);
        } else {
            $this->setInvalidData($attribute, $value);
        }
    }

    /**
     * @param string $key
     * @param $rules
     * @throws RuleException
     */
    protected function addAttribute(string $key, $rules): void
    {
        if (strpos($key, ':') !== false) {
            [$key, $alias] = explode(':', $key);
            $this->aliases[$key] = $alias;
        }

        $this->attributes->add($key, new Attribute($this, $key, $this->alias($key), $this->resolveRules($rules)));
    }

    /**
     * @param $rules
     * @return array|string
     * @throws RuleException
     */
    protected function resolveRules($rules): array
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $resolvedRules = [];

        foreach ($rules as $i => $rule) {
            if (empty($rule)) {
                continue;
            }

            if (is_string($i) && is_scalar($rule)) {
                $rule = sprintf('%s:%s', $i, $rule);
            }

            if (is_string($rule)) {
                [$rulename, $params] = $this->parseRule($rule);
                $validator = $this->factory->rule($rulename)->fillParameters($params);
            } elseif ($rule instanceof Rule) {
                $validator = $rule;
            } elseif ($rule instanceof Closure) {
                $validator = $this->factory->rule('callback')->fillParameters([$rule]);
            } else {
                throw RuleException::invalidRuleType(is_object($rule) ? get_class($rule) : gettype($rule));
            }

            $resolvedRules[] = $validator;
        }

        return $resolvedRules;
    }

    /**
     * @param string $rule
     * @return array
     */
    protected function parseRule(string $rule): array
    {
        $exp = explode(':', $rule, 2);
        $ruleName = $exp[0];

        if (in_array($ruleName, ['matches', 'regex'])) {
            $params = [$exp[1]];
        } else {
            $params = isset($exp[1]) ? str_getcsv($exp[1]) : [];
        }

        return [$ruleName, $params];
    }

    /**
     * @return Factory
     */
    public function factory(): Factory
    {
        return $this->factory;
    }

    /**
     * @param string $attributeKey
     * @return string|null
     */
    public function alias(string $attributeKey): ?string
    {
        return $this->aliases[$attributeKey] ?? null;
    }

    /**
     * @param string $attributeKey
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $attributeKey, string $alias): self
    {
        $this->aliases[$attributeKey] = $alias;

        return $this;
    }

    /**
     * @param string $lang
     * @return $this
     */
    public function setLanguage(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return AttributeBag
     */
    public function attributes(): AttributeBag
    {
        return $this->attributes;
    }

    /**
     * Check whether given $attribute is an array attribute
     *
     * @param Attribute $attribute
     * @return bool
     */
    protected function isArrayAttribute(Attribute $attribute): bool
    {
        return strpos($attribute->key(), '*') !== false;
    }

    /**
     * Parse array attribute into it's child attributes
     *
     * @param Attribute $attribute
     * @return array
     */
    protected function parseArrayAttribute(Attribute $attribute): array
    {
        $attributeKey = $attribute->key();
        $data = Helper::arrayDot($this->initializeAttributeOnData($attributeKey));
        $pattern = str_replace('\*', '([^\.]+)', preg_quote($attributeKey));

        $data = array_merge($data, $this->extractValuesForWildcards(
            $data,
            $attributeKey
        ));

        $attributes = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^' . $pattern . '\z/', $key, $match)) {
                $attr = new Attribute($this, $key, null, $attribute->rules()->all());
                $attr->setParent($attribute);
                $attr->setIndexes(array_slice($match, 1));
                $attributes[] = $attr;
            }
        }

        return $attributes;
    }

    /**
     * Gather a copy of the attribute data filled with any missing attributes.
     * Adapted from: https://github.com/illuminate/validation/blob/v5.3.23/Validator.php#L334
     *
     * @param string $attributeKey
     * @return array
     */
    protected function initializeAttributeOnData(string $attributeKey): array
    {
        $explicitPath = $this->getLeadingExplicitAttributePath($attributeKey);

        $data = $this->extractDataFromPath($explicitPath);

        $asteriskPos = strpos($attributeKey, '*');

        if (false === $asteriskPos || $asteriskPos === (mb_strlen($attributeKey, 'UTF-8') - 1)) {
            return $data;
        }

        return Helper::arraySet($data, $attributeKey, null);
    }

    /**
     * Get the explicit part of the attribute name.
     *
     * Adapted from: https://github.com/illuminate/validation/blob/v5.3.23/Validator.php#L2817
     *
     * E.g. 'foo.bar.*.baz' -> 'foo.bar'
     *
     * Allows skipping flattened data for some operations.
     */

    /**
     * Get the explicit part of the attribute name.
     * Adapted from: https://github.com/illuminate/validation/blob/v5.3.23/Validator.php#L2817
     * E.g. 'foo.bar.*.baz' -> 'foo.bar'
     * Allows skipping flattened data for some operations.
     *
     * @param string $attributeKey
     * @return string|null
     */
    protected function getLeadingExplicitAttributePath(string $attributeKey): ?string
    {
        return rtrim(explode('*', $attributeKey)[0], '.') ?: null;
    }

    /**
     * Extract data based on the given dot-notated path.
     * Adapted from: https://github.com/illuminate/validation/blob/v5.3.23/Validator.php#L2830
     * Used to extract a subsection of the data for faster iteration.
     *
     * @param string|null $attributeKey
     * @return array
     */
    protected function extractDataFromPath(?string $attributeKey): array
    {
        $results = [];

        $value = $this->input->get($attributeKey, '__missing__');

        if ($value != '__missing__') {
            Helper::arraySet($results, $attributeKey, $value);
        }

        return $results;
    }

    /**
     * Get all the attribute values for a given wildcard attribute.
     * Adapted from: https://github.com/illuminate/validation/blob/v5.3.23/Validator.php#L354
     *
     * @param array $data
     * @param string $attributeKey
     * @return array
     */
    private function extractValuesForWildcards(array $data, string $attributeKey): array
    {
        $keys = [];

        $pattern = str_replace('\*', '[^\.]+', preg_quote($attributeKey));

        foreach ($data as $key => $value) {
            if (preg_match('/^' . $pattern . '/', $key, $matches)) {
                $keys[] = $matches[0];
            }
        }

        return $this->input->only(...array_unique($keys));
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isEmptyValue($value): bool
    {
        return false === (new Required)->check($value);
    }

    /**
     * @param Attribute $attribute
     * @param Rule $rule
     * @return bool
     */
    protected function ruleIsOptional(Attribute $attribute, Rule $rule): bool
    {
        return
            false === $attribute->isRequired() &&
            false === $rule->isImplicit() &&
            false === $rule instanceof Required;
    }

    /**
     * @param Attribute $attribute
     * @param Rule $rule
     * @param $value
     */
    protected function addError(Attribute $attribute, Rule $rule, $value): void
    {
        $this->errors->add($attribute->key(), $rule->name(), $this->resolveMessage($attribute, $rule, $value));
    }

    /**
     * @param Attribute $attribute
     * @return string
     */
    protected function resolveAttributeName(Attribute $attribute): string
    {
        return $this->aliases[$attribute->key()] ??
            (!is_null($attribute->parent()) && !isset($this->aliases[$attribute->parent()->key()])
                ? $this->aliases[$attribute->parent()->key()]
                : $attribute->key());
    }

    /**
     * @param Attribute $attribute
     * @param Rule $rule
     * @param $value
     * @return ErrorMessage
     * @throws Exceptions\MessageException
     */
    protected function resolveMessage(Attribute $attribute, Rule $rule, $value): ErrorMessage
    {
        $primaryAttribute = $attribute->parent();
        $attributeKey = $attribute->key();
        $ruleName = $rule->name();
        $message = $rule->message(['attribute' => $this->resolveAttributeName($attribute), 'value' => $value]);
        $messageKeys = [
            $attributeKey . $this->separator . $ruleName,
            $attributeKey,
            $message->key(),
        ];

        if ($primaryAttribute) {
            $primaryAttributeKey = $primaryAttribute->key();
            // adds additional key lookups in the message keys e.g. parent.*.attribute:rule
            array_splice($messageKeys, 1, 0, $primaryAttributeKey . $this->separator . $ruleName);
            array_splice($messageKeys, 3, 0, $primaryAttributeKey);
        }

        $message->setMessage($this->messages->firstOf($messageKeys, $this->lang));

        // Replace key indexes
        $keyIndexes = $attribute->indexes();

        // add placeholders for [0] or {1} to params set
        foreach ($keyIndexes as $pathIndex => $index) {
            $replacers = [sprintf('[%s]', $pathIndex) => $index];

            if (is_numeric($index)) {
                $replacers[sprintf('{%s}', $pathIndex)] = $index + 1;
            }

            $message->addParams($replacers);
        }

        return $message;
    }

    /**
     * @return InputBag
     */
    public function input(): InputBag
    {
        return $this->input;
    }

    /**
     * @return MessageBag
     */
    public function messages(): MessageBag
    {
        return $this->messages;
    }

    /**
     * @return ErrorBag
     */
    public function errors(): ErrorBag
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * @return bool
     */
    public function passes(): bool
    {
        return $this->errors->count() === 0;
    }

    /**
     * @return array
     */
    public function getValidatedData(): array
    {
        return array_merge($this->validData, $this->invalidData);
    }

    /**
     * @return array
     */
    public function getValidData(): array
    {
        return $this->validData;
    }

    /**
     * @param Attribute $attribute
     * @param $value
     */
    protected function setValidData(Attribute $attribute, $value): void
    {
        $key = $attribute->key();

        if ($attribute->isArrayAttribute() || $attribute->isUsingDotNotation()) {
            Helper::arraySet($this->validData, $key, $value);
            Helper::arrayUnset($this->invalidData, $key);
        } else {
            $this->validData[$key] = $value;
        }
    }

    /**
     * @return array
     */
    public function getInvalidData(): array
    {
        return $this->invalidData;
    }

    /**
     * @param Attribute $attribute
     * @param $value
     */
    protected function setInvalidData(Attribute $attribute, $value): void
    {
        $key = $attribute->key();

        if ($attribute->isArrayAttribute() || $attribute->isUsingDotNotation()) {
            Helper::arraySet($this->invalidData, $key, $value);
            Helper::arrayUnset($this->validData, $key);
        } else {
            $this->invalidData[$key] = $value;
        }
    }
}
