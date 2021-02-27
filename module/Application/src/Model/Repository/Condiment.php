<?php


namespace Application\Model\Repository;



use App\Model\Repository\AbstractRepository;

class Condiment extends AbstractRepository
{
    public function __construct()
    {
        $this->setEntityClassName(\Application\Model\Entity\Condiment::class);
    }
}
