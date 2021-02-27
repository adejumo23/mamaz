<?php


namespace Application\Model\Service;


use App\Di\InjectableInterface;
use Application\Model\Entity\Cart as CartEntity;
use Application\Model\Entity\DetailedCart;
use Application\Model\Entity\DetailedMenuItem;
use Application\Model\Repository\Cart;

class CartService implements InjectableInterface
{
    /**
     * @var Cart
     * @Inject(name="Application\Model\Repository\Cart")
     */
    protected $cartRepo;
    /**
     * @var MenuItemService
     * @Inject(name="Application\Model\Service\MenuItemService")
     */
    protected $menuItemService;

    /**
     * @param $userId
     * @return CartEntity|null
     */
    public function getCart($userId)
    {
        $cartItem = $this->cartRepo->findOneBy(['user_id' => $userId]);
        return $cartItem;
    }

    /**
     * @param CartEntity $cartItem
     * @return DetailedCart
     */
    public function getDetailedCart($cartItem)
    {
        $cartItems = $cartItem->getFormData();
        $detailedCart = new DetailedCart();

        foreach ((array)$cartItems as $item) {
            $menuItem = $this->menuItemService->getMenuItem($item['menuitemId']);
            $detailedMenuItem = new DetailedMenuItem();
            $detailedMenuItem->setMenuItem($menuItem);
            $detailedMenuItem->setId($item['id']);
            $condiments = $item['condiments'];
            $price = $menuItem->getPrice();
            foreach ($menuItem->getCondiments() as $condiment) {
                if (in_array($condiment->getId(), array_keys($condiments))
                    &&  (int)$condiments[$condiment->getId()] > 0
                ) {
                    $detailedMenuItem->addCondiment($condiment);
                    $detailedMenuItem->setCondimentCount($condiment->getId(), (int)$condiments[$condiment->getId()]);
                    $price += $condiment->getPrice() * (int)$condiments[$condiment->getId()];
                    $detailedMenuItem->setPrice($price);
                }
            }
            $detailedCart->addDetailedMenuItem($detailedMenuItem);
        }
        return $detailedCart;
    }

    public function clearCart($userId)
    {
        /** @var CartEntity $cartItem */
        $cartItem = $this->cartRepo->findOneBy(['user_id' => $userId]);
        $this->cartRepo->deleteBy(['id' => $cartItem->getId()]);
    }

    /**
     * @param $id
     * @param CartEntity $cartItem
     */
    public function removeItemFromCart($id,$cartItem)
    {
        $menuItems = $cartItem->getFormData();
        $saveChange = false;
        foreach($menuItems as $index => $menuItem){
            if($menuItem['id'] == $id){
                unset($menuItems[$index]);
                $saveChange = true;
            }
        }
        if ($saveChange) {
            $cartItem->setFormData((array)$menuItems);
            $this->saveCart($cartItem);
        }
    }
    /**
     * @param Cart $cartRepo
     * @return CartService
     */
    public function setCartRepo($cartRepo)
    {
        $this->cartRepo = $cartRepo;
        return $this;
    }

    /**
     * @param \Application\Model\Entity\Cart $cartDetail
     */
    public function saveCart($cartDetail)
    {
        $this->updateCartPrice($cartDetail);
        $this->cartRepo->save($cartDetail);
    }

    /**
     * @param CartEntity $cartDetail
     */
    public function updateCart($cartDetail)
    {
        $this->updateCartPrice($cartDetail);
        $cartDetail->setFormData(json_encode($cartDetail->getFormData()));
        $this->cartRepo->update((array)$cartDetail);
    }

    /**
     * @param MenuItemService $menuItemService
     * @return CartService
     */
    public function setMenuItemService($menuItemService)
    {
        $this->menuItemService = $menuItemService;
        return $this;
    }

    /**
     * @param CartEntity $cartDetail
     */
    protected function updateCartPrice(CartEntity $cartDetail)
    {
        $totalPrice = 0.00;
        $cartItems = $cartDetail->getFormData();
        foreach ($cartItems as $cartItem) {
            $menuItem = $this->menuItemService->getMenuItem($cartItem['menuitemId']);
            $totalPrice += $menuItem->getPrice();
            $condiments = $menuItem->getCondiments();
            foreach ((array)$condiments as $condiment) {
                $totalPrice += $condiment->getPrice() * (int)$cartItem['condiments'][$condiment->getId()];
            }
        }
        $cartDetail->setPrice($totalPrice);
    }

}