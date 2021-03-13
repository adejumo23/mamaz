<?php


namespace Application\Model\Service;

use App\Di\InjectableInterface;
use Application\Model\Entity\DetailedCart;
use Application\Model\Entity\DetailedMenuItem;
use Application\Model\Entity\Order as OrderEntity;
use Application\Model\Repository\Order;

class OrderService implements InjectableInterface
{

    const ORDER_STATUS_IN_QUEUE = 'In Queue';
    const ORDER_STATUS_STARTED = 'Started';
    const ORDER_STATUS_READY = 'Ready';
    const ORDER_STATUS_PICKED_UP = 'Picked Up';

    public static $orderStatusLookup = [
        self::ORDER_STATUS_IN_QUEUE => 1,
        self::ORDER_STATUS_STARTED => 2,
        self::ORDER_STATUS_READY => 3,
        self::ORDER_STATUS_PICKED_UP => 4,
    ];

    /**
     * @var CartService
     * @Inject(name="Application\Model\Service\CartService")
     */
    protected $cartService;

    /**
     * @var Order
     * @Inject(name="Application\Model\Repository\Order")
     */
    protected $orderRepo;
    /**
     * @param OrderEntity $orderDetail
     */
    public function saveCheckout($orderDetail)
    {
        $dailyOrderId = $this->orderRepo->findLatestDailyOrderId()  + 1;
        $orderDetail->setDailyOrderId($dailyOrderId);
        $this->orderRepo->save($orderDetail);
    }

    public function getTotalPrice(){
        $orderPrice =  $this->orderRepo->getTotalOrderPrice();
        return $orderPrice;
    }
    /**
     * @param OrderEntity $orderDetail
     */
    public function updateOrder(OrderEntity $orderDetail)
    {
        $this->orderRepo->update((array)$orderDetail);
    }

    /**
     * @return OrderEntity $order
     */
    public function getOrderStatus()
    {
        return $this->orderRepo->findAll();

    }
    /**
     * @return DetailedCart[]
     */

    public function getAllKitchenOrders(){
        $orders = $this->orderRepo->findBy([
            'status' => [
                self::$orderStatusLookup[self::ORDER_STATUS_IN_QUEUE],
                self::$orderStatusLookup[self::ORDER_STATUS_STARTED]
            ]
        ]);
        /** @var OrderEntity $order */
        foreach ($orders as &$order){
            $cartItem = $this->cartService->getCartById($order->getCartId());
            $detailedCart = $this->cartService->getDetailedCart($cartItem);
            $order->setCart($detailedCart);
        }
        return $orders;
    }

    /**
     * @param $orderId
     * @return OrderEntity
     */
    public function getOrder($orderId)
    {
        $orderItem = $this->orderRepo->findOneBy(['id' => $orderId]);
        return $orderItem;
    }

    /**
     * @return int
     */
    public function getTotalOrderCount(){
        return $this->orderRepo->getOrderCount();
    }
    /**
     * @return Order
     */
    public function getOrderRepo()
    {
        return $this->orderRepo;
    }

    /**
     * @param Order $orderRepo
     * @return OrderService
     */
    public function setOrderRepo($orderRepo)
    {
        $this->orderRepo = $orderRepo;
        return $this;
    }

    /**
     * @param CartService $cartService
     * @return OrderService
     */
    public function setCartService($cartService)
    {
        $this->cartService = $cartService;
        return $this;
    }

}