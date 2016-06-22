<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * User
 *
 * @ORM\Table(name="ss__user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\XmlRoot("user")
 * 
 *  @Hateoas\Relation(
 *      name="self",
 *      href = @Hateoas\Route(
 *          "api_1_get_user",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *          }
 *      ),
 *      exclusion = @Hateoas\Exclusion( groups={"list"})
 * )
 */
class User {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"list", "details"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"list", "details"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"details"})
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"details"})
     */
    private $lastname;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default" = 0})
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"details"})
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * 
     * @Serializer\Expose
     * @Serializer\Groups({"details"})
     */
    private $createdAt;

    /**
     * @var ArrayCollection list of groups
     * 
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     */
    protected $groups;

    public function __construct() {
        $this->groups = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled = false) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * check if user is enabled
     *
     * @return boolean 
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Add groups
     *
     * @param Group $group
     * @return User
     */
    public function addGroup(Group $group) {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param Group $group
     */
    public function removeGroup(Group $group) {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGroups() {
        return $this->groups;
    }

}
