<?php

namespace AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\AbstractType;
use AppBundle\Handler\Exception\InvalidFormException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Description of AbstractHandler
 *
 * @author skander
 */
abstract class ResourceHandler
{
    /**
     * @var Doctrine\Common\Persistence\ObjectManager $om 
     */
    protected $om;

    /**
     * @var FQCN of the Entity
     */
    protected $entityClass;

    /**
     * @var Doctrine\ORM\EntityRepository $repository
     */
    protected $repository;

    /**
     * @var Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * @var Symfony\Component\Form\AbstractType $formType
     */
    protected $formType;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(ObjectManager $om, 
                                FormFactoryInterface $formFactory,
                                RequestStack $requestStack)
    {
        $this->om = $om;
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * should be implemented by children classes. should return an instance of Symfony\Component\Form\AbstractType
     */
    abstract public function getFormType();

    /**
     * Should be implemented by children classes. should Return The FQCN of the entity
     */
    abstract public function getEntityClass();

    /**
     * Will be executed before processing request
     */
    abstract public function preProcess($entity);

    /**
     * @throws \InvalidArgumentException
     */
    public function setup()
    {
        $this->formType = $this->getFormType();
        if (!$this->formType instanceof AbstractType) {
            throw new \InvalidArgumentException('method getFormType should return an AbstractType instance. given: ' . get_class($this->formType));
        }

        $this->entityClass = $this->getEntityClass();
        if (empty($this->entityClass)) {
            throw new \InvalidArgumentException('method getEntityClass should return the FQCN of the entity. Null was given');
        }

        $this->repository = $this->om->getRepository($this->entityClass);
    }

    /**
     * Processes the form.
     *
     * @param String $method
     *
     * @return Entity
     *
     * @throws AppBundle\Handler\Exception\InvalidFormException
     */
    private function processForm($method = "PUT", $entity = null)
    {
        $this->setup();
        if (!$entity) {
            $entity = new $this->entityClass();
        }

        $entity = $this->preProcess($entity);

        $form = $this->formFactory->create($this->formType, $entity,
                                           array('method' => $method));
        $form->handleRequest($this->request, 'PATCH' !== $method);
        if ($form->isValid()) {
            $entity = $this->prePersist($form->getData());
            $this->om->persist($entity);
            $this->om->flush($entity);
            $this->postPersist($entity);

            return $entity;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    protected function prePersist($entity)
    {
        return $entity;
    }

    protected function postPersist($entity)
    {
        return $entity;
    }

    public function delete($entity)
    {
        
    }

    public function find($criteria)
    {
        
    }

    public function get($id)
    {
        
    }

    public function post()
    {
        return $this->processForm('POST');
    }

    public function put($entity)
    {
        return $this->processForm('PUT', $entity);
    }

}
