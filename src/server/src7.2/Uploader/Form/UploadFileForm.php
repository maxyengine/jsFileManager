<?php

namespace Nrg\Uploader\Form;

use Nrg\FileManager\Form\File\Element\PathElement;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;
use Nrg\Uploader\Form\Element\UploadFileElement;

/**
 * Class UploadFileForm
 */
class UploadFileForm extends Form
{
    /**
     * @param Translator $translator
     * @param PathElement $pathElement
     * @param UploadFileElement $uploadFileElement
     */
    public function __construct(
        Translator $translator,
        PathElement $pathElement,
        UploadFileElement $uploadFileElement
    ) {
        parent::__construct($translator);

        $this
            ->addElement($pathElement)
            ->addElement($uploadFileElement);
    }
}