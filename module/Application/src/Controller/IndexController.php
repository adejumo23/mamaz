<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Entity\Cart as CartItem;
use Application\Model\Entity\Order as OrderItem;
use Application\Model\Entity\MenuItem;
use Application\Model\Service\CartService;
use Application\Model\Service\MenuItemService;
use Application\Model\Service\OrderService;
use DateTime;
use DateTimeZone;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\Date;
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

    /**
     * @var OrderService
     * @Inject(name="Application\Model\Service\OrderService")
     */
    protected $orderService;

    public function indexAction()
    {
        $userId = $this->getOrCreateUserId();
        /** cart item returns as an object so treat as such*/
        $cartItem = $this->cartService->getCartByUserId($userId);
        $cartCount = 0;
        /**
         * need to revisit this idea
         * dont forget this!
         */
        if (!$cartItem) {
            $cartItem = new CartItem();
        }
        if ($cartItem->getFormData()) {
            $cartItems = $cartItem->getFormData();
            $cartCount = count($cartItems);
            $totalPrice = $cartItem->getPrice();
        }
        $this->cartDetailing($cartItem, $cartCount, $totalPrice);
        return new ViewModel();
    }

    public function getOrCreateUserId()
    {
        session_start();
        if (!$_SESSION['user_id']) {
            $userId = $this->cartService->getNewUserId();
            $_SESSION['user_id'] = $userId;
        }else{
            $userId = $_SESSION['user_id'];
        }
        return $userId;
    }

    public function menu()
    {
        $userId = $this->getOrCreateUserId();
        $cartItem = $this->cartService->getCartByUserId($userId);
        $cartCount = 0;
        if (!$cartItem) {
            $cartItem = new CartItem();
            $totalPrice = 0.00;
        } else {
            $cartCount = count($cartItem->getFormData());
            $totalPrice = $cartItem->getPrice();
        }

        $this->cartDetailing($cartItem, $cartCount, $totalPrice);

    }

    public function menuAction()
    {
        $this->menu();
        $menuItems = $this->menuItemService->getMenuItems();
//        $menuItems = $this->menuItemService->getMenuPartialItems();
        $view = new ViewModel(['menuItems' => $menuItems]);
        $view->setTemplate('application/index/menu');
        return $view;
    }

    public function menuitemAction()
    {
        $this->menu();
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
        $userId = $this->getOrCreateUserId();
        $cart = $this->cartService->getCartByUserId($userId);
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
        $userId = $this->getOrCreateUserId();
        $this->cartService->clearCart($userId);
        $cartItem = $this->cartService->getCartByUserId($userId);
        if (!$cartItem) {
            $cartCount = 0;
        }
        $this->layout()->cartCount = $cartCount;
        return $this->redirect()->toRoute('application');
    }

    public function kitchenOrdersAction()
    {
        $detailedOrders = $this->orderService->getAllKitchenOrders();
        $totalOrderPrice = $this->orderService->getTotalPrice();
        $totalOrderCount = $this->orderService->getTotalOrderCount();
        $this->layout()->totalPrice = number_format($totalOrderPrice, 2);
        $this->layout()->cartCount = $totalOrderCount;
        $view = new ViewModel(['detailedOrders'=> $detailedOrders]);
        $view->setTemplate('application/index/kitchen-orders');
        return $view;
    }

    public function statusTableAction()
    {
        /*$orders = [
            [
                'name' => 'id',
                'status' => OrderService::ORDER_STATUS_STARTED,
            ],
            [
                'name' => 'raj',
                'status' => OrderService::ORDER_STATUS_IN_QUEUE,
            ],
            [
                'name' => 'daisy',
                'status' => OrderService::ORDER_STATUS_READY,
            ]
        ];*/
        $orders = $this->orderService->getOrderStatus();
        $view = new ViewModel(['orders' => $orders]);
        $view->setTemplate('application/index/status-table');
        return $view;
    }

    public function editCartAction()
    {
        $userId = $this->getOrCreateUserId();
        $cartItem = $this->cartService->getCartByUserId($userId);
        if (!$cartItem) {
            return $this->redirect()->toRoute('application');
        }
        $id = $this->params('id');
        $this->cartService->removeItemFromCart($id, $cartItem);
        $cartCount = count($cartItem->getFormData());
        $this->layout()->cartCount = $cartCount;
        return $this->redirect()->toRoute('application');
    }

    public function cartAction()
    {
        $userId = $this->getOrCreateUserId();
        /** cart item returns as an object so treat as such*/
        $cartItem = $this->cartService->getCartByUserId($userId);
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
    public function createOrderAction(){
        $userId = $this->getOrCreateUserId();
        $post = $this->params()->fromPost();
        $username = $post['username'];
        $cart = new CartItem();
        $cart->setUserId($userId);
        $cart->setUsername($username);
        $this->cartService->saveCart($cart);
        $_SESSION['username'] = $username;
        return $this->redirect()->toRoute('application:menu');
    }

    public function checkoutAction(){
        $userId = $this->getOrCreateUserId();
        $cartItem = $this->cartService->getCartByUserId($userId);
        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, date(date('Y-m-d H:i:s', time())));
        $order = new OrderItem();
        $order->setUsername($cartItem->getUsername());
        $order->setUserId($cartItem->getUserId());
        $order->setCartId($cartItem->getId());
        $order->setPrice($cartItem->getPrice());
        $order->setOrderTime($date);
        $order->setStatus(OrderService::ORDER_STATUS_IN_QUEUE);
        $this->orderService->saveCheckout($order);
        session_destroy();
        return $this->redirect()->toRoute('application');
    }

    public function updateOrderStatusAction(){
        $id = $this->params('id');
        $status = $this->params('status');
        $time = date('Y-m-d H:i:s');
        $orderItem = $this->orderService->getOrder($id);
        if($status == 3){
            $orderItem->setReadyTime((new DateTime));
        }
        else{
            if($orderItem->getReadyTime()){
                $orderItem->setReadyTime($orderItem->getReadyTime());
            }
            else{
                $orderItem->setReadyTime('0000-00-00 00:00:00');
            }
        }
        $orderItem->setPickupTime('0000-00-00 00:00:00');
        $orderItem->setStatus($status);
        $this->orderService->updateOrder($orderItem);
        return $this->redirect()->toRoute('application:kitchen-orders');
    }
    /**
     * @return viewmodel
     */

    public function newOrderAction(){
        $view = new ViewModel();
        $view->setTemplate('application/index/order-name.phtml');
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
    public function cartDetailing($cartItem, $cartCount, $totalPrice)
    {
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

    /**
     * @return OrderService
     */
    public function getOrderService()
    {
        return $this->orderService;
    }

    /**
     * @param OrderService $orderService
     * @return IndexController
     */
    public function setOrderService($orderService)
    {
        $this->orderService = $orderService;
        return $this;
    }

}
