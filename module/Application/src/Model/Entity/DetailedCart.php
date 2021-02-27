<?php


namespace Application\Model\Entity;


class DetailedCart
{
    /**
     * @var DetailedMenuItem[]
     */
    public $menuItems;

    public function addDetailedMenuItem(DetailedMenuItem $detailedMenuItem)
    {
        $this->menuItems[] = $detailedMenuItem;
    }

    /**
     * @return DetailedMenuItem[]
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @param DetailedMenuItem[] $menuItems
     * @return DetailedCart
     */
    public function setMenuItems($menuItems)
    {
        $this->menuItems = $menuItems;
        return $this;
    }
}