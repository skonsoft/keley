<?php

namespace AppBundle\Handler\Exception;

/**
 * Description of InvalidFormException
 *
 * @author skander
 */
class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

}
