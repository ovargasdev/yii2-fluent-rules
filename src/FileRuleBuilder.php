<?php

namespace ovargas\fluentrules;

/**
 * FileRuleBuilder helps build the 'file' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\FileValidator` to ensure that the attribute receives a correctly uploaded file, complying with size, extension, and MIME type restrictions.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class FileRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for the file validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'file');
    }

    /**
     * Specifies the allowed file extensions.
     * Can be passed as an array or a comma- or space-separated string.
     *
     * @param array|string $extensions Valid extensions (e.g., ['png', 'jpg'] or "png, jpg").
     * @return $this
     */
    public function extensions(array|string $extensions): static
    {
        $this->_params['extensions'] = $extensions;
        return $this;
    }

    /**
     * Specifies the allowed MIME types.
     * Can be passed as an array or a comma- or space-separated string.
     *
     * @param array|string $types Valid MIME types (e.g., ['image/jpeg', 'image/png'] or "image/*").
     * @return $this
     */
    public function mimeTypes(array|string $types): static
    {
        $this->_params['mimeTypes'] = $types;
        return $this;
    }

    /**
     * Sets the minimum allowed file size in bytes.
     *
     * @param int $bytes Size in bytes.
     * @return $this
     */
    public function minSize(int $bytes): static
    {
        $this->_params['minSize'] = $bytes;
        return $this;
    }

    /**
     * Sets the maximum allowed file size in bytes.
     *
     * @param int $bytes Size in bytes.
     * @return $this
     */
    public function maxSize(int $bytes): static
    {
        $this->_params['maxSize'] = $bytes;
        return $this;
    }

    /**
     * Defines the maximum number of files the attribute can contain (multiple uploads).
     *
     * @param int $count Maximum number of allowed files.
     * @return $this
     */
    public function maxFiles(int $count): static
    {
        $this->_params['maxFiles'] = $count;
        return $this;
    }

    /**
     * Determines whether the file extension should be checked against its actual MIME content.
     * This prevents users from tricking the system by renaming a malicious .exe file to .jpg.
     *
     * @param bool $check By default true.
     * @return $this
     */
    public function checkExtensionByMimeType(bool $check = true): static
    {
        $this->_params['checkExtensionByMimeType'] = $check;
        return $this;
    }

    /**
     * Determines whether the file validation rule should be skipped when no file has been uploaded.
     *
     * @param bool $skip By default true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }
}