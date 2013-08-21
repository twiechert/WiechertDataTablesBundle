<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tayfun
 * Date: 12.08.13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

namespace Wiechert\DataTablesBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Wiechert\DataTablesBundle\Annotations\DisplayName;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="position")
 * @DisplayName(name="Position")
 */
class Position {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"Id"})
     * @DisplayName(name="Id")
     */
    protected $id;


    /**
     * @ORM\Column(type="decimal")
     * @Serializer\Groups({"Simple"})
     * @DisplayName(name="Amount")
     */
    protected $amount;

    
    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"Simple"})
     * @DisplayName(name="Quantity")
     */
    protected $quantity;



    /**
     * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\Product")
     * @Serializer\Groups({"SimpleReference"})
     * @DisplayName(name="Product")
     **/
    protected $product;


    /**
     * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\Order")
     * @Serializer\Groups({"SimpleReference"})
     * @DisplayName(name="Order")
     **/
    protected $order;

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
     * Set amount
     *
     * @param integer $amount
     * @return Position
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \Wiechert\DataTablesBundle\Entity\Product $product
     * @return Position
     */
    public function setProduct(\Wiechert\DataTablesBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Wiechert\DataTablesBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param \Wiechert\DataTablesBundle\Entity\Order $order
     * @return Position
     */
    public function setOrder(\Wiechert\DataTablesBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Wiechert\DataTablesBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Position
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
