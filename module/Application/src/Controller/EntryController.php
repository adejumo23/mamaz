<?php


namespace Application\Controller;


use App\Di\InjectableInterface;
use Application\Form\EntryForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EntryController extends AbstractActionController implements InjectableInterface
{
    /**
     * @var EntryForm
     * @Inject(name="Application\Form\EntryForm")
     */
    protected $entryForm;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->entryForm->prepare();
        $view = new ViewModel();
        $view->setTemplate('entry/entryform');
        $view->setVariable('form', $this->entryForm);
        return $view;
    }

    /**
     * @param EntryForm $entryForm
     * @return EntryController
     */
    public function setEntryForm($entryForm)
    {
        $this->entryForm = $entryForm;
        return $this;
    }

}