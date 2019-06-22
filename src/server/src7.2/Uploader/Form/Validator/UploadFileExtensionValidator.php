<?php

namespace Nrg\Uploader\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;
use Nrg\Http\Value\UploadedFile;
use Nrg\Utility\Abstraction\Config;

/**
 * Class UploadFileExtensionValidator
 */
class UploadFileExtensionValidator extends AbstractValidator
{
    public const CASE_ERROR_EXTENSION = 0;

    /**
     * @var array|null
     */
    private $allowExtensions;

    /**
     * @var array|null
     */
    private $denyExtensions;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->allowExtensions = $config->get('allowExtensions');
        $this->denyExtensions = $config->get('denyExtensions');
        $this->adjustErrorText('the file type is not allowed', self::CASE_ERROR_EXTENSION);
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        $this->setErrorCase(self::CASE_ERROR_EXTENSION);

        /**
         * @var $uploadedFile UploadedFile
         */
        $uploadedFile = $element->getValue();

        if (null !== $this->denyExtensions) {
            return !in_array($uploadedFile->getExtension(), $this->denyExtensions);
        }

        if (null !== $this->allowExtensions) {
            return in_array($uploadedFile->getExtension(), $this->allowExtensions);
        }

        return true;
    }
}