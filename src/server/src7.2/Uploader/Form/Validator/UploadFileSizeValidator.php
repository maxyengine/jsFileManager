<?php

namespace Nrg\Uploader\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;
use Nrg\Http\Value\UploadedFile;
use Nrg\Utility\Abstraction\Config;
use Nrg\Utility\Value\Size;

/**
 * Class UploadFileSizeValidator
 */
class UploadFileSizeValidator extends AbstractValidator
{
    public const CASE_MAX_SIZE = 0;

    /**
     * @var Size|null
     */
    private $maxSize;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->maxSize = $config->get('maxSize', null);
        $this->adjustErrorText('the file size should not exceed %s', self::CASE_MAX_SIZE);
    }

    /**
     * @param Element $element
     *
     * @return bool
     */
    public function isValid(Element $element): bool
    {
        $this->setErrorCase(self::CASE_MAX_SIZE, null === $this->maxSize ? '' : $this->maxSize->toHumanString());
        /**
         * @var $uploadedFile UploadedFile
         */
        $uploadedFile = $element->getValue();

        return null === $this->maxSize || $uploadedFile->getSize() <= $this->maxSize->getValue();
    }
}