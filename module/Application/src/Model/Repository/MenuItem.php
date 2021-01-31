<?php


namespace Application\Model\Repository;



use App\Model\Repository\AbstractRepository;

class MenuItem extends AbstractRepository
{
    public function __construct()
    {
        $this->setEntityClassName(\Application\Model\Entity\MenuItem::class);
    }


}