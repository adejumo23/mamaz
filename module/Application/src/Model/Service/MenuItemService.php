<?php


namespace Application\Model\Service;


use App\Di\InjectableInterface;
use Application\Model\Repository\Cart;
use Application\Model\Repository\Condiment;
use Application\Model\Repository\MenuItem;
use Application\Model\Repository\MenuItemCondiment;

class MenuItemService implements InjectableInterface
{

    /**
     * @var MenuItem
     * @Inject(name="Application\Model\Repository\MenuItem")
     */
    protected $menuItemRepo;

    /**
     * @var Condiment
     * @Inject(name="Application\Model\Repository\Condiment")
     */
    protected $condimentRepo;

    /**
     * @var MenuItemCondiment
     * @Inject(name="Application\Model\Repository\MenuItemCondiment")
     */
    protected $menuitemCondimentRepo;




    /**
     * @return array
     */
    public function getMenuItems()
    {
        /** @var \Application\Model\Entity\MenuItem[] $menuItems */
        $menuItems = $this->menuItemRepo->findBy(['category < 5']);
        /** @var \Application\Model\Entity\Condiment[] $condimentsList */
        $condimentsList = $this->condimentRepo->findAll();
        foreach ($menuItems as $menuItem) {
            $condiments = $this->menuitemCondimentRepo->findBy(['menuitem_id' => $menuItem->getId()]);
            foreach ($condimentsList as $condiment) {
                if (in_array($condiment->getId(), $this->getCondimentIds($condiments))) {
                    $menuItem->addCondiment($condiment);
                }
            }
            $result[]['menuItem'] = $menuItem;
        }
        return $result;
    }

    /**
     * @param $id
     * @return \Application\Model\Entity\MenuItem
     */
    public function getMenuItem($id)
    {
        /** @var \Application\Model\Entity\Condiment[] $condimentsList */
        $condimentsList = $this->condimentRepo->findAll();

        $result = $this->menuItemRepo->findBy([
            'id' => $id
        ]);
        /** @var \Application\Model\Entity\MenuItem $menuItem */
        $menuItem = reset($result);

        $condiments = $this->menuitemCondimentRepo->findBy(['menuitem_id' => $menuItem->getId()]);
        foreach ($condimentsList as $condiment) {
            if (in_array($condiment->getId(), $this->getCondimentIds($condiments))) {
                $menuItem->addCondiment($condiment);
            }
        }
        return $menuItem;
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

    /**
     * @param Condiment $condimentRepo
     * @return MenuItemService
     */
    public function setCondimentRepo($condimentRepo)
    {
        $this->condimentRepo = $condimentRepo;
        return $this;
    }

    /**
     * @param MenuItemCondiment $menuitemCondimentRepo
     * @return MenuItemService
     */
    public function setMenuitemCondimentRepo($menuitemCondimentRepo)
    {
        $this->menuitemCondimentRepo = $menuitemCondimentRepo;
        return $this;
    }

    /**
     * @param array $condiments
     * @return int[]
     */
    protected function getCondimentIds(array $condiments): array
    {
        return array_map(
            function (\Application\Model\Entity\MenuItemCondiment $item) {
                return $item->getCondimentId();
            }, $condiments);
    }

}