<?php

namespace Nrg\Uploader\Form\Element;

use Nrg\Form\Element;
use Nrg\Uploader\Form\Validator\UploadFileExtensionValidator;
use Nrg\Uploader\Form\Validator\UploadFileSizeValidator;

/**
 * Class UploadFileElement
 */
class UploadFileElement extends Element
{
    /**
     * @param UploadFileExtensionValidator $extensionValidator
     * @param UploadFileSizeValidator $sizeValidator
     * @param string $name
     */
    public function __construct(
        UploadFileExtensionValidator $extensionValidator,
        UploadFileSizeValidator $sizeValidator,
        string $name = 'file'
    ) {
        parent::__construct($name);

        $this
            ->addValidator($extensionValidator)
            ->addValidator($sizeValidator);
    }
}