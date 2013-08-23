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
 * @ORM\Table(name="category")
 * @DisplayName(name="Category")
 */
class Category
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
    * @DisplayName(name="Label")
    */
    protected $label;



    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="rootcategory")
     * @Serializer\Groups({"ComplexeReference"})
    **/
    protected $subcategories;



    /**
     * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\User", inversedBy="categories")
     * @Serializer\Groups({"SimpleReference"})
     * @DisplayName(name="Creator")
     **/
    protected $creator;

    /**
     * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\Category", inversedBy="subcategories")
     * @Serializer\Groups({"SimpleReference"})
     * @DisplayName(name="Root Category")
     **/
    protected $rootcategory;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="categories")
     * @Serializer\Groups({"ComplexeReference"})
     * @DisplayName(name="Products")
     */
    protected $products;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subCategories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set label
     *
     * @param string $label
     * @return Category
     */
    public function setLabel($label)
    {
        $this->label = $label;
    

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add subCategories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $subCategories
     * @return Category
     */
    public function addSubcategorie(Category $subCategories)
    {
        $this->subcategories[] = $subCategories;
    
        return $this;
    }

    /**
     * Remove subCategories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $subCategories
     */
    public function removeSubcategorie(Category $subCategories)
    {
        $this->subcategories->removeElement($subCategories);
    }

    /**
     * Get subCategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    /**
     * Set rootCategory
     *
     * @param \Wiechert\DataTablesBundle\Entity\Kategorie $rootCategory
     * @return Category
     */
    public function setRootcategory(Category $rootCategory = null)
    {
        $this->rootCctegory = $rootCategory;
    
        return $this;
    }

    /**
     * Get rootCategory
     *
     * @return \Wiechert\DataTablesBundle\Entity\Kategorie
     */
    public function getRootcategory()
    {
        return $this->rootcategory;
    }



    /**
     * Add subCategories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $subCategories
     * @return Category
     */
    public function addSubcategory(\Wiechert\DataTablesBundle\Entity\Category $subCategories)
    {
        $this->subcategories[] = $subCategories;

        return $this;
    }

    /**
     * Remove subCategories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $subCategories
     */
    public function removeSubcategory(\Wiechert\DataTablesBundle\Entity\Category $subCategories)
    {
        $this->subcategories->removeElement($subCategories);
    }

    /**
     * Set creator
     *
     * @param \Wiechert\DataTablesBundle\Entity\User $creator
     * @return Category
     */
    public function setCreator(\Wiechert\DataTablesBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Wiechert\DataTablesBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add products
     *
     * @param \Wiechert\DataTablesBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Wiechert\DataTablesBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Wiechert\DataTablesBundle\Entity\Product $products
     */
    public function removeProduct(\Wiechert\DataTablesBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
