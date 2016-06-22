<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="ss__user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity("email")
 * 
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\XmlRoot("user")
 * 
 *  @Hateoas\Relation(
 *      name="self",
 *      href = @Hateoas\Route(
 *          "get_user",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *          }
 *      ),
 *      exclusion = @Hateoas\Exclusion( groups={"list", "Default"})
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
     * @Assert\NotBlank(message="L'email doit être renseigné")
     * @Assert\Length(
     *      min = "5",
     *      max = "255",
     *      minMessage = "L'email doit faire au moins {{ limit }} caractères",
     *      maxMessage = "L'email  ne peut pas être plus long que {{ limit }} caractères"
     * )
     * 
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide.",
     *     checkMX = true,
     *     strict = false
     * )
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
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Le prénom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le prénom  ne peut pas être plus long que {{ limit }} caractères"
     * )
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
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le nom  ne peut pas être plus long que {{ limit }} caractères"
     * )
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
    private $enabled = false;

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
