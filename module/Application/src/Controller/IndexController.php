<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

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

    public function indexAction()
    {
        return new ViewModel();
    }

    public function menuAction()
    {
        $menuItems = $this->menuItemService->getMenuItems();

        $view =  new ViewModel(['menuItems' => $menuItems]);
        $view->setTemplate('application/index/menu');
        return $view;
    }
    public function menuitemAction()
    {
        $id = $this->params('id');
        $menuItem = $this->menuItemService->getMenuItem($id);

        $view =  new ViewModel(['menuItem' => $menuItem]);
        $view->setTemplate('application/index/menuitem');
        return $view;
    }

    public function orderStatusAction()
    {
        $view =  new ViewModel();
        $view->setTemplate('application/index/orderstatus.phtml');
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
}
