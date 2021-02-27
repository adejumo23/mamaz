<?php


namespace Application\Model\Repository;
use App\Model\Repository\AbstractRepository;
use Application\Model\Entity\Cart as CartItem;

class Cart extends AbstractRepository
{

    public function __construct()
    {
        $this->setEntityClassName(CartItem::class);
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

}