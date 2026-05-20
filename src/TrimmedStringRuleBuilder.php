<?php

namespace ovargas\fluentrules;

/**
 * TrimmedStringRuleBuilder is a composite constructor (Composite).
 *
 * It fluidly encapsulates two independent native Yii2 validators: `trim` and `string`.
 * It ensures that user data is first trimmed of leading and trailing whitespace before evaluating
 * the actual text length constraints, preventing length failures due to accidental spaces.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class TrimmedStringRuleBuilder extends RuleBuilder
{
    /**
     * Internal instance responsible for structuring the string length validator.
     */
    private StringRuleBuilder $stringBuilder;

    /**
     * Internal instance responsible for structuring the whitespace trimming filter.
     */
    private TrimRuleBuilder $trimBuilder;

    /**
     * Initializes a new instance for the composite validation of cleaned strings.
     *
     * @param Attribute $attribute The attribute associated with the rules.
     * @param int|null $length Maximum string length for StringRuleBuilder.
     */
    public function __construct(Attribute $attribute, ?int $length = null)
    {
        // The 'composite' type is an internal organizational flag; it will not be exported when calling make().
        parent::__construct($attribute, 'composite');

        $this->trimBuilder = new TrimRuleBuilder($attribute);
        $this->stringBuilder = new StringRuleBuilder($attribute, $length);
    }

    /**
     * Sets a global custom error message for the text length validator.
     *
     * @param string $message Custom error message.
     * @return $this
     */
    public function message(string $message): static
    {
        $this->stringBuilder->message($message);
        return $this;
    }

    /**
     * Sets the maximum allowed character length for the trimmed text string.
     *
     * @param int $length Maximum length in characters.
     * @return $this
     */
    public function max(int $length): static
    {
        $this->stringBuilder->max($length);
        return $this;
    }

    /**
     * Sets the minimum required character length for the trimmed text string.
     *
     * @param int $length Minimum length in characters.
     * @return $this
     */
    public function min(int $length): static
    {
        $this->stringBuilder->min($length);
        return $this;
    }

    /**
     * Removes the maximum length restriction, retaining only the initial trim action.
     *
     * @return $this
     */
    public function asText(): static
    {
        $this->stringBuilder->asText();
        return $this;
    }

    /**
     * Overrides the parent class method to export multiple valid rules for Yii2.
     *
     * Instead of returning a single flat rule array structure, it returns a sequential array containing the two native configurations structured independently.
     * The order is critical: Trim runs first to clean the data, then String validates it.
     *
     * @return array Sequential array of rule configurations ready for the model's rules() method.
     */
    public function make(): array
    {
        return [
            $this->trimBuilder->make(),
            $this->stringBuilder->make()
        ];
    }
}