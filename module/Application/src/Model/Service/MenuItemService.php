<?php


namespace Application\Model\Service;


use App\Di\InjectableInterface;
use Application\Model\Repository\MenuItem;

class MenuItemService implements InjectableInterface
{

    /**
     * @var MenuItem
     * @Inject(name="Application\Model\Repository\MenuItem")
     */
    protected $menuItemRepo;


    public function getMenuItems()
    {
        $menuItems = $this->menuItemRepo->findAll();
        foreach ($menuItems as $menuItem) {
            $result[]['menuItem'] = $menuItem;
        }
        return $result;
    }
    public function getMenuItem($id)
    {
        $result = $this->menuItemRepo->findBy([
            'id' => $id
        ]);
        return reset($result);
    }

    /**
     * @param MenuItem $menuItemRepo
     * @return MenuItemService
     */
    public function setMenuItemRepo($menuItemRepo)
    {
        $this->menuItemRepo = $menuItemRepo;
        return $this;
    }

}