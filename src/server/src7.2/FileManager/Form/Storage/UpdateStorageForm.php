<?php

namespace Nrg\FileManager\Form\Storage;

use Nrg\FileManager\Form\Storage\Element\DescriptionElement;
use Nrg\FileManager\Form\Storage\Element\NameElement;
use Nrg\FileManager\Form\Storage\Validator\UniqueNameValidator;
use Nrg\FileManager\UseCase\Storage\IsUniqueName;
use Nrg\Form\Element\UuidElement;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

class UpdateStorageForm extends Form
{
    /**
     * @var IsUniqueName
     */
    private $isUniqueName;

    public function __construct(Translator $translator, IsUniqueName $isUniqueName)
    {
        parent::__construct($translator);

        $this->isUniqueName = $isUniqueName;

        $this
            ->addElement(new UuidElement())
            ->addElement(new NameElement())
            ->addElement(new DescriptionElement());
    }

    /**
     * {@inheritdoc}
     */
    public function populate(array $data): Form
    {
        parent::populate($data);

        $id = $this->getElement('id');

        if (!$id->hasError()) {
            $this->getElement('name')->addValidator((new UniqueNameValidator($this->isUniqueName)));
        }

        return $this;
    }
}
