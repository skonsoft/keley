<?php

namespace AppBundle\Handler;

use AppBundle\Form\UserType;

/**
 * Description of AbuseHandler
 *
 * @author skander
 */
class UserHandler extends ResourceHandler
{

    public function getEntityClass()
    {
        return 'AppBundle\Entity\User';
    }

    /**
     * @param UserType $formType
     */
    public function setFormType(UserType $formType)
    {
        $this->formType = $formType;
    }

    public function getFormType()
    {
        return $this->formType;
    }

    public function preProcess($entity)
    {
        return $entity;
    }

    protected function postPersist($entity)
    {
        return $entity;
    }

}
