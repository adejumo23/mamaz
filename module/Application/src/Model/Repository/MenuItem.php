<?php


namespace Application\Model\Repository;



use App\Model\Repository\AbstractRepository;

class MenuItem extends AbstractRepository
{
    private $getPartialMenuItems;

    public function __construct()
    {
        $this->setEntityClassName(\Application\Model\Entity\MenuItem::class);
    }
    /**
     * @return MenuItem
     */
    public function getPartialMenuItems(){
        $query = "SELECT * FROM MENUITEMS WHERE CATEGORY < 5";

    }


}
