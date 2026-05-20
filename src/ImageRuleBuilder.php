<?php

namespace ovargas\fluentrules;

/**
 * ImageRuleBuilder helps build the 'image' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\ImageValidator`. Inherits all file
 * validation properties and introduces specific geometric dimension restrictions
 * for graphic files using the GD2 or Imagick libraries.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class ImageRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for the image validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'image');
    }

    /**
     * Specifies the allowed image file extensions.
     *
     * @param array|string $extensions Valid extensions (e.g., ['png', 'jpg'] or 'png, gif').
     * @return $this
     */
    public function extensions(array|string $extensions): static
    {
        $this->_params['extensions'] = $extensions;
        return $this;
    }

    /**
     * Sets the minimum allowed width for the image in pixels.
     *
     * @param int $pixels Minimum width in px.
     * @return $this
     */
    public function minWidth(int $pixels): static
    {
        $this->_params['minWidth'] = $pixels;
        return $this;
    }

    /**
     * Sets the maximum allowed width for the image in pixels.
     *
     * @param int $pixels Maximum width in px.
     * @return $this
     */
    public function maxWidth(int $pixels): static
    {
        $this->_params['maxWidth'] = $pixels;
        return $this;
    }

    /**
     * Sets the minimum allowed height for the image in pixels.
     *
     * @param int $pixels Minimum height in px.
     * @return $this
     */
    public function minHeight(int $pixels): static
    {
        $this->_params['minHeight'] = $pixels;
        return $this;
    }

    /**
     * Sets the maximum allowed height for the image in pixels.
     *
     * @param int $pixels Maximum height in px.
     * @return $this
     */
    public function maxHeight(int $pixels): static
    {
        $this->_params['maxHeight'] = $pixels;
        return $this;
    }

    /**
     * Defines the maximum number of image files allowed in multiple uploads.
     *
     * @param int $count Maximum number of images.
     * @return $this
     */
    public function maxFiles(int $count): static
    {
        $this->_params['maxFiles'] = $count;
        return $this;
    }

    /**
     * Determines whether the image's geometric validation is skipped if no file has been uploaded.
     *
     * @param bool $skip Default is true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }
}