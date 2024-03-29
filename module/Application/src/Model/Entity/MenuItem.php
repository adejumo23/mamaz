<?php


namespace Application\Model\Entity;


use App\Model\Entity\AbstractEntity;

class MenuItem extends AbstractEntity
{

    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $itemName;

    /**
     * @var string
     */
    protected $itemDesc;
    /**
     * @var string
     */
    protected $imageMain;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var int
     */
    protected $category;

    /**
     * @var Condiment[]
     */
    protected $condiments = [];

    public function getMetadata()
    {
        $this->setRepositoryName(\Application\Model\Repository\MenuItem::class);
        $this->setTable('menuitems');
        $this->setField('id', 'int', null, true);
        $this->setField('itemname', null,'itemName');
        $this->setField('itemdesc', null,'itemDesc');
        $this->setField('image_main',null, 'imageMain');
        $this->setField('price', 'float','price');
        $this->setField('category', 'int');

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MenuItem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param string $itemName
     * @return MenuItem
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemDesc()
    {
        return $this->itemDesc;
    }

    /**
     * @param string $itemDesc
     * @return MenuItem
     */
    public function setItemDesc($itemDesc)
    {
        $this->itemDesc = $itemDesc;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageMain()
    {
        return $this->imageMain;
    }

    /**
     * @param string $imageMain
     * @return MenuItem
     */
    public function setImageMain($imageMain)
    {
        $this->imageMain = $imageMain;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return MenuItem
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     * @return MenuItem
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
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
     * @return MenuItem
     */
    public function setCondiments($condiments)
    {
        $this->condiments = $condiments;
        return $this;
    }
    /**
     * @param Condiment $condiment
     * @return MenuItem
     */
    public function addCondiment($condiment)
    {
        $this->condiments[] = $condiment;
        return $this;
    }
}