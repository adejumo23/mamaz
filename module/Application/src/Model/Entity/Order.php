<?php


namespace Application\Model\Entity;


use App\Model\Entity\AbstractEntity;
use Application\Model\Service\OrderService;
use phpDocumentor\Reflection\Types\AbstractList;

class Order extends AbstractEntity implements \JsonSerializable
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $userId;
    /**
     * @var mixed
     */
    public $dailyOrderId = 1;
    /**
     * @var float
     */
    public $price = 0.00;
    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $status = '';

    /**
     * @var string
     */
    public $pickupName = '';

    /**
     * @var int
     */
    public $cartId;
    /**
     * @var DetailedCart
     */
    public $cart;

    public function preSaveHook()
    {
        if ($this->status) {
            $this->status = OrderService::$orderStatusLookup[$this->status];
        }
    }

    public function postFetchHook()
    {
        if ($this->status) {
            $this->status = array_flip(OrderService::$orderStatusLookup)[$this->status];
        }
    }
    /**
     * @var \DateTime
     */

    public $orderTime;
    /**
     * @var \DateTime
     */
    public $pickupTime;

    /**
     * @var \DateTime
     */
    public $readyTime;

    public function getMetadata()
    {
        $this->setRepositoryName(\Application\Model\Repository\Order::class);
        $this->setTable('orders');
        $this->setField('id', 'int', null, true);
        $this->setField('user_id', null, 'userId');
        $this->setField('daily_order_id', null, 'dailyOrderId');
        $this->setField('price', 'float', 'price');
        $this->setField('username', null, 'username');
        $this->setField('status', null, 'status');
        $this->setField('pickup_name', null, 'pickupName');
        $this->setField('cart_id', null, 'cartId');
        $this->setField('pickup_time', 'datetime', 'pickupTime');
        $this->setField('ready_time', 'datetime', 'readyTime');
        $this->setField('order_time', 'datetime', 'orderTime');
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
     * @return Order
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return Order
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return array
     */
    public function getDailyOrderId()
    {
        return $this->dailyOrderId;
    }

    /**
     * @param int $dailyOrderId
     * @return Order
     */
    public function setDailyOrderId($dailyOrderId)
    {
        $this->dailyOrderId = $dailyOrderId;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'dailyOrderId' => $this->dailyOrderId,
            'cartId' => $this->cartId,
            'price' => $this->price,
            'orderTime' => $this->orderTime,
            'readyTime' => $this->readyTime,
            'pickupTime' => $this->pickupTime,
        ];
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
     * @return Order
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Order
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPickupName()
    {
        return $this->pickupName;
    }

    /**
     * @param string $pickupName
     * @return Order
     */
    public function setPickupName($pickupName)
    {
        $this->pickupName = $pickupName;
        return $this;
    }

    /**
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @param int $cartId
     * @return Order
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param \DateTime $orderTime
     * @return Order
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPickupTime()
    {
        return $this->pickupTime;
    }

    /**
     * @param \DateTime $pickupTime
     * @return Order
     */
    public function setPickupTime($pickupTime)
    {
        $this->pickupTime = $pickupTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReadyTime()
    {
        return $this->readyTime;
    }

    /**
     * @param \DateTime $readyTime
     * @return Order
     */
    public function setReadyTime($readyTime)
    {
        $this->readyTime = $readyTime;
        return $this;
    }

    /**
     * @return DetailedCart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param DetailedCart $cart
     * @return Order
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
        return $this;
    }
}
