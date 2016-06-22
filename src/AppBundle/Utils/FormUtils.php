<?php

namespace AppBundle\Utils;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of FormUtils
 *
 * @author skander
 */
class FormUtils
{

    /**
     *
     * @var TranslatorInterface 
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @return array
     */
    public function getFormErrors(FormInterface $form)
    {
        $errors = array();
        //
        if ($err = $this->childErrors($form)) {
            $errors['errors'] = $err;
        }
        //
        foreach ($form->all() as $key => $child) {
            //
            if ($err = $this->getFormErrors($child)) {
                $errors[$key] = $err;
            }
        }
        return $errors;
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    public function childErrors(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            /** @Ignore */
            $message = $this->translator->trans($error->getMessage(), array(), 'validators');
            array_push($errors, $message);
        }
        return $errors;
    }

}
