<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * sGroup
 *
 * @ORM\Table(name="ss__group")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GroupRepository")
 * 
 * @UniqueEntity("name")
 * 
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\XmlRoot("group")
 * 
 *  @Hateoas\Relation(
 *      name="self",
 *      href = @Hateoas\Route(
 *          "get_group",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *          }
 *      ),
 *      exclusion = @Hateoas\Exclusion( groups={"list", "Default"})
 * )
 * 
 */
class Group {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le nom  ne peut pas être plus long que {{ limit }} caractères"
     * )
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"list", "details"})
     */
    private $name;

    /**
     * @var ArrayCollection list of users
     * 
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return sGroup
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add users
     *
     * @param User $user
     * @return Group
     */
    public function addUser(User $user) {
        $user->addGroup($this);

        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user) {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers() {
        return $this->users;
    }

}
