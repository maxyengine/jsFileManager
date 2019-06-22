<?php

namespace Nrg\Data\Form;

use Nrg\Data\Element\Filter;
use Nrg\Data\Element\Limit;
use Nrg\Data\Element\Offset;
use Nrg\Data\Element\OrderBy;
use Nrg\Form\Form;
use Nrg\I18n\Abstraction\Translator;

/**
 * Class ListForm
 */
abstract class ListForm extends Form
{
    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        parent::__construct($translator);
        // todo:: i18n for Nrg\Data\Form

        $this
            ->addElement((new Limit()))
            ->addElement(
                (new OrderBy())
                    ->setAllowedFields($this->getOrderByAllowedFields())
            )
            ->addElement(
                (new Filter())
                    ->setAllowedFields($this->getFilterAllowedFields())
            )
            ->addElement(new Offset())
        ;
    }

    /**
     * @return array
     */
    abstract protected function getOrderByAllowedFields(): array;

    /**
     * @return array
     */
    abstract protected function getFilterAllowedFields(): array;
}