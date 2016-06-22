<?php

namespace AppBundle\Handler;

use AppBundle\Form\GroupType;

/**
 * Description of GroupHandler
 *
 * @author skander
 */
class GroupHandler extends ResourceHandler
{

    public function getEntityClass()
    {
        return 'AppBundle\Entity\Group';
    }

    /**
     * @param UserType $formType
     */
    public function setFormType(GroupType $formType)
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
