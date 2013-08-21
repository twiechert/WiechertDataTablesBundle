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
 * @ORM\Table(name="xorder")
 * @DisplayName(name="Order")
 */
class Order
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
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"Simple"})
     * @DisplayName(name="Total Amount")
     */
    protected $totalamount;


    /**
     * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\User", inversedBy="categories")
     * @Serializer\Groups({"SimpleReference"})
     * @DisplayName(name="Creator")
     **/
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Position", mappedBy="order")
     * @Serializer\Groups({"ComplexeReference"})
     * @DisplayName(name="Positions")
     */
    protected $positions;



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
     * Set totalAmount
     *
     * @param integer $totalAmount
     * @return Order
     */
    public function setTotalamount($totalamount)
    {
        $this->totalamount = $totalamount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return integer 
     */
    public function getTotalamount()
    {
        return $this->totalamount;
    }

    /**
     * Set user
     *
     * @param \Wiechert\DataTablesBundle\Entity\User $user
     * @return Order
     */
    public function setUser(\Wiechert\DataTablesBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wiechert\DataTablesBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->positions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add positions
     *
     * @param \Wiechert\DataTablesBundle\Entity\Position $positions
     * @return Order
     */
    public function addPosition(\Wiechert\DataTablesBundle\Entity\Position $positions)
    {
        $this->positions[] = $positions;

        return $this;
    }

    /**
     * Remove positions
     *
     * @param \Wiechert\DataTablesBundle\Entity\Position $positions
     */
    public function removePosition(\Wiechert\DataTablesBundle\Entity\Position $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPositions()
    {
        return $this->positions;
    }
}
