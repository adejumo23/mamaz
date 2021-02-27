<?php


namespace Application\Model\Repository;



use App\Model\Repository\AbstractRepository;

class MenuItemCondiment extends AbstractRepository
{
    public function __construct()
    {
        $this->setEntityClassName(\Application\Model\Entity\MenuItemCondiment::class);
    }
}
