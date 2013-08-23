<?php
/**
 * A simple Domain Class representing any kind of Product/Item.
 * Every product can belong to one ore more categories.
 *
 * @author Tayfun Wiechert <tayfun.wiechert@gmail.com>
 */
namespace Wiechert\DataTablesBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

use Wiechert\DataTablesBundle\Annotations\DisplayName;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @DisplayName(name="Product")
 */
  class Product
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
    * @ORM\Column(type="string", length=45)
    * @Serializer\Groups({"super", "Simple"})
    * @DisplayName(name="Label")
    */
    protected $label;


   /**
    * @ORM\Column(type="text")
    * @Serializer\Groups({"Simple"})
    * @DisplayName(name="Description")
    */
    protected $description;


    /**
     * @ORM\Column(type="decimal")
     * @Serializer\Groups({"Simple"})
     * @DisplayName(name="Amount")
      */
     protected $amount;

    
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @ORM\JoinTable(name="product_category_relation",
     *                joinColumns={@ORM\JoinColumn(name="f_product_id", referencedColumnName="id")},
     *                inverseJoinColumns={@ORM\JoinColumn(name="f_category_id", referencedColumnName="id")})
     * @Serializer\Groups({"ComplexeReference"})
     * @DisplayName(name="Categories")
     */
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
     * Set label
     *
     * @param string $label
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add categories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $categories
     * @return Product
     */
    public function addCategorie(\Wiechert\DataTablesBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $categories
     */
    public function removeCategorie(\Wiechert\DataTablesBundle\Entity\Category $categories)
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

    /**
     * Add categories
     *
     * @param \Wiechert\DataTablesBundle\Entity\Category $categories
     * @return Product
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
     * Set amount
     *
     * @param float $amount
     * @return Product
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
