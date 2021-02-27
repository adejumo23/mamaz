<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Entity\Cart as CartItem;
use Application\Model\Entity\MenuItem;
use Application\Model\Service\CartService;
use Application\Model\Service\MenuItemService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var MenuItemService
     * @Inject(name="Application\Model\Service\MenuItemService")
     */
    protected $menuItemService;
    /**
     * @var CartService
     * @Inject(name="Application\Model\Service\CartService")
     */
    protected $cartService;



    public function indexAction()
    {
        $userId = $_COOKIE['userid']??1;
        /** cart item returns as an object so treat as such*/
        $cartItem = $this->cartService->getCart($userId);
        $cartCount = 0;
        /**
         * need to revisit this idea
         * dont forget this!
         */
        if(!$cartItem){
            $cartItem = new CartItem();
        }
        if($cartItem->getFormData()){
            $cartItems = $cartItem->getFormData();
            $cartCount = count($cartItems);
            $totalPrice = $cartItem->getPrice();
        }
/*        $detailedCart = $this->cartService->getDetailedCart($cartItem);
        $cartView = new ViewModel();
        $cartView->setTemplate('application/index/cart');
        $cartView->setVariable('detailedCart', $detailedCart);
        $this->layout()->setVariable('cartCount', $cartCount);
        $this->layout()->setVariable('totalPrice', number_format($totalPrice, 2));
        $this->layout()->setVariable('cartView', $cartView);*/
        $this->cartDetailing($cartItem,$cartCount,$totalPrice);
        return new ViewModel();
    }

    public function menuAction()
    {
        $userId = $_COOKIE['userid']??1;
        $cartItem = $this->cartService->getCart($userId);
        $cartCount = 0;
        if (!$cartItem) {
            $cartItem = new CartItem();
            $totalPrice = 0.00;
        }else{
            $cartCount = count($cartItem->getFormData());
            $totalPrice = $cartItem->getPrice();
        }
/*        $this->layout()->setVariable('cartCount', $cartCount);
        $this->layout()->setVariable('totalPrice', number_format($totalPrice, 2));*/
        $this->cartDetailing($cartItem,$cartCount,$totalPrice);
        $menuItems = $this->menuItemService->getMenuItems();
//        $menuItems = $this->menuItemService->getMenuPartialItems();
        $view = new ViewModel(['menuItems' => $menuItems]);
        $view->setTemplate('application/index/menu');
        return $view;
    }

    public function menuitemAction()
    {
        $userId = $_COOKIE['userid']??1;
        $cartItem = $this->cartService->getCart($userId);
        $cartCount = 0;
        if(!$cartItem){
            $cartItem = new CartItem();
            $totalPrice = 0.00;
        }
        else{
            $cartCount = count($cartItem->getFormData());
            $totalPrice = $cartItem->getPrice();
        }
/*        $this->layout()->setVariable('cartCount', $cartCount);
        $this->layout()->setVariable('totalPrice', number_format($totalPrice, 2));*/
        $this->cartDetailing($cartItem,$cartCount,$totalPrice);

        $id = $this->params('id');
        $menuItem = $this->menuItemService->getMenuItem($id);
        $view = new ViewModel(['menuItem' => $menuItem]);
        $view->setTemplate('application/index/orderitem');
        return $view;
    }

    public function orderStatusAction()
    {
        $view = new ViewModel();
        $view->setTemplate('application/index/orderstatus.phtml');
        return $view;
    }

    public function addToCartAction()
    {
        $id = $this->params('id');
        $post = $this->params()->fromPost();
        $condiments = $post['condiments'];
        $userId = $_COOKIE['userid']??1;
        $cart = $this->cartService->getCart($userId);
        if (!$cart) {
            $formData[] = $this->addOrCreateFormData($id, $condiments);
            $cart = new CartItem();
            $cart->setUserId($userId);
            $cart->setFormData($formData);
            $this->cartService->saveCart($cart);
        } else {
            $formData = $cart->getFormData();
            $formData[] = $this->addOrCreateFormData($id, $condiments);
            $cart->setFormData($formData);
            $this->cartService->updateCart($cart);
        }
        return $this->redirect()->toRoute('application:menu');

    }


    public function clearCartAction()
    {
        $userId = $_COOKIE['userid']??1;
        $this->cartService->clearCart($userId);
        $cartItem = $this->cartService->getCart($userId);
        $cartCount = count($cartItem->getFormData());
        $this->layout()->cartCount =  $cartCount;
        return $this->redirect()->toRoute('application');
    }
    public function editCartAction()
    {
        $userId = $_COOKIE['userid']??1;
//        $this->cartService->clearCart($userId);
        $cartItem = $this->cartService->getCart($userId);
        if(!$cartItem){
            return $this->redirect()->toRoute('application');         }
        $id = $this->params('id');
        $this->cartService->removeItemFromCart($id,$cartItem);
        $cartCount = count($cartItem->getFormData());
        $this->layout()->cartCount =  $cartCount;
        return $this->redirect()->toRoute('application');
    }

    public function cartAction(){
        $userId = $_COOKIE['userid']??1;
        /** cart item returns as an object so treat as such*/
        $cartItem = $this->cartService->getCart($userId);
        $cartCount = 0;
        if(!$cartItem){
            $cartItem = new CartItem();
        }
        if($cartItem->getFormData()){
            $cartItems = $cartItem->getFormData();
            $cartCount = count($cartItems);
            $totalPrice = $cartItem->getPrice();
        }
        $detailedCart = $this->cartService->getDetailedCart($cartItem);
        $view = new ViewModel();
        $view->setTemplate('application/index/cart');
        $view->setVariable('detailedCart', $detailedCart);
        $view->setVariable('totalPrice', number_format($totalPrice, 2));
        $this->layout()->setVariable('cartCount', $cartCount);
        $this->layout()->setVariable('totalPrice', number_format($totalPrice, 2));
        $this->layout()->setVariable('cartView', $view);
        return $view;
    }

    /**
     * @param MenuItemService $menuItemService
     * @return IndexController
     */
    public function setMenuItemService($menuItemService)
    {
        $this->menuItemService = $menuItemService;
        return $this;
    }

    /**
     * @param CartService $cartService
     * @return IndexController
     */
    public function setCartService($cartService)
    {
        $this->cartService = $cartService;
        return $this;
    }
    protected $cartDetailing;
    /**
     * @var $cartItem
     * @var $totalPrice
     * @var $cartCount
     */
    public function cartDetailing($cartItem,$cartCount,$totalPrice){
        $detailedCart = $this->cartService->getDetailedCart($cartItem);
        $cartView = new ViewModel();
        $cartView->setTemplate('application/index/cart');
        $cartView->setVariable('detailedCart', $detailedCart);
        $this->layout()->setVariable('cartCount', $cartCount);
        $this->layout()->setVariable('totalPrice', number_format($totalPrice, 2));
        $this->layout()->setVariable('cartView', $cartView);
    }

    /**
     * @param $id
     * @param $condiments
     * @return array
     * @throws \Exception
     */
    protected function addOrCreateFormData($id, $condiments)
    {
        return [
            'id' => md5(random_bytes(32)),
            'menuitemId' => $id,
            'condiments' => $condiments,
        ];
    }

}
