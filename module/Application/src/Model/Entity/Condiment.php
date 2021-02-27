<?php


namespace Application\Model\Entity;


use App\Model\Entity\AbstractEntity;

class Condiment extends AbstractEntity
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

    public function getMetadata()
    {
        $this->setRepositoryName(\Application\Model\Repository\Condiment::class);
        $this->setTable('condiments');
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
     * @return Condiment
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
     * @return Condiment
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
     * @return Condiment
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
     * @return Condiment
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
     * @return Condiment
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
     * @return Condiment
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

}