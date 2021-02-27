<?php
namespace Application\Model\Entity;

use App\Model\Entity\AbstractEntity;
use Application\Model\Repository\MenuItemCondiment as MenuItemCondimentRepo;

class MenuItemCondiment extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $menuitemId;

    /**
     * @var int
     */
    protected $condimentId;

    public function getMetadata()
    {
        $this->setRepositoryName(MenuItemCondimentRepo::class);
        $this->setTable('menuitem_condiments');
        $this->setField('id', 'int', null, true);
        $this->setField('menuitem_id', 'int', 'menuitemId');
        $this->setField('condiment_id', 'int', 'condimentId');
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
     * @return MenuItemCondiment
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getMenuitemId()
    {
        return $this->menuitemId;
    }

    /**
     * @param int $menuitemId
     * @return MenuItemCondiment
     */
    public function setMenuitemId($menuitemId)
    {
        $this->menuitemId = $menuitemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCondimentId()
    {
        return $this->condimentId;
    }

    /**
     * @param int $condimentId
     * @return MenuItemCondiment
     */
    public function setCondimentId($condimentId)
    {
        $this->condimentId = $condimentId;
        return $this;
    }

}