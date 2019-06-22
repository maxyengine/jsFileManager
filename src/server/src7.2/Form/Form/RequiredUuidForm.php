<?php

namespace Nrg\Form\Form;

use Nrg\Form\Element\UuidElement;
use Nrg\Form\Form;

/**
 * Class RequiredUuidForm.
 */
class RequiredUuidForm extends Form
{
    /**
     * @param string $elementName
     */
    public function __construct(string $elementName = 'id')
    {
        parent::__construct();

        $this->addElement(new UuidElement($elementName));
    }
}
