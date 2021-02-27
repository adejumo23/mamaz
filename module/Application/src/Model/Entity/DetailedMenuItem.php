<?php


namespace Application\Model\Entity;


class DetailedMenuItem
{

    /**
     * @var Condiment[]
     */
    public $condiments = [];
    /**
     * @var MenuItem
     */
    public $menuItem;
    /**
     * @var array
     */
    public $condimentCount = [];
    /**
     * @var float
     */
    public $price;
    /**
     * @var string
     */
    public $id;

    public function addCondiment(Condiment $condiment)
    {
        $this->condiments[$condiment->getId()] = $condiment;
    }

    public function setMenuItem(MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function setCondimentCount($condimentId, $count)
    {
        $this->condimentCount[$condimentId] = $count;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return Condiment[]
     */
    public function getCondiments()
    {
        return $this->condiments;
    }

    /**
     * @param Condiment[] $condiments
     * @return DetailedMenuItem
     */
    public function setCondiments($condiments)
    {
        $this->condiments = $condiments;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return DetailedMenuItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}