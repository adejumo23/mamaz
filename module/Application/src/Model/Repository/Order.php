<?php


namespace Application\Model\Repository;


use App\Model\Repository\AbstractRepository;

class Order extends AbstractRepository
{
    public function __construct()
    {
        $this->setEntityClassName(\Application\Model\Entity\Order::class);
    }

    public function save($cartItem)
    {
        if ($cartItem->getId()) {
            $cartItem->setFormData(json_encode($cartItem->getFormData()));
            parent::update($cartItem);
            $cartItem->setFormData(json_decode($cartItem->getFormData(), true));
            return $cartItem;
        }
        $id = parent::save($cartItem);
        $cartItem->setId($id);
        return $cartItem;
    }

    public function findLatestDailyOrderId()
    {
        $query =<<<SQL
SELECT orders.daily_order_id FROM orders WHERE year(order_time) = year(NOW()) and month(order_time) = month(now()) and day(order_time) = day(now()) ORDER BY daily_order_id desc limit 1
SQL;

        $result = $this->executeQuery($query);
        $result = reset($result);
        return (int)($result['daily_order_id']);
    }
    public function getTotalOrderPrice(){
        $query =<<<SQL
SELECT SUM(price) as price FROM orders;
SQL;
        $result = $this->executeQuery($query);
        $result = reset($result);
        return $result['price'];
    }
    public function getOrderCount(){
        $query =<<<SQL
SELECT count(id) as counter FROM orders;
SQL;
        $result = $this->executeQuery($query);
        $result = reset($result);
        return $result['counter'];
    }
}