<?php
/**
 * A simple Domain Class representing a category.
 * The category class has to two self referencing relationships.
 *
 * There is is a One-To-Many-Relationship to reference all subcategories and
 * a Many-To-Tone-Relationship to reference the root category.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Wiechert\DataTablesBundle\Annotations\DisplayName;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @DisplayName(name="User")
 */
class User
{
     /**
      * @ORM\Id
      * @ORM\Column(type="integer", name="id")
      * @ORM\GeneratedValue(strategy="AUTO")
      * @Serializer\Groups({"Id"})
      * @DisplayName(name="Id")
      */
     protected $id;
     

   /**
    * @ORM\Column(type="string", length=60)
    * @Serializer\Groups({"Simple", "Name"})
    * @DisplayName(name="Username")
    */
    protected $username;



    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="creator")
     * @Serializer\Groups({"ComplexeReference"})
    **/
    protected $categories;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Add categories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $categories
     * @return User
     */
    public function addCategory(\Wiechert\DataTablesBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $categories
     */
    public function removeCategory(\Wiechert\DataTablesBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
