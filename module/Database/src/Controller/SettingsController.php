<?php

namespace Database\Controller;

use Database\Model\Member;
use Database\Service\InstallationFunction as InstallationFunctionService;
use Database\Service\MailingList as MailingListService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SettingsController extends AbstractActionController
{
    /** @var InstallationFunctionService $installationFunctionService */
    private $installationFunctionService;

    /** @var MailingListService $mailingListService */
    private $mailingListService;

    public function __construct(
        InstallationFunctionService $installationFunctionService,
        MailingListService $mailingListService
    ) {
        $this->installationFunctionService = $installationFunctionService;
        $this->mailingListService = $mailingListService;
    }

    /**
     * Index action.
     */
    public function indexAction()
    {
        return new ViewModel(array());
    }

    /**
     * Function action.
     */
    public function functionAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->installationFunctionService->addFunction($this->getRequest()->getPost());
        }

        return new ViewModel(array(
            'functions' => $this->installationFunctionService->getAllFunctions(),
            'form' => $this->installationFunctionService->getFunctionForm()
        ));
    }

    /**
     * Mailing list action
     */
    public function listAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->mailingListService->addList($this->getRequest()->getPost());
        }

        return new ViewModel(array(
            'lists' => $this->mailingListService->getAllLists(),
            'form' => $this->mailingListService->getListForm()
        ));
    }

    /**
     * List deletion action
     */
    public function deleteListAction()
    {
        $name = $this->params()->fromRoute('name');

        if ($this->getRequest()->isPost()) {
            if ($this->mailingListService->delete($name, $this->getRequest()->getPost())) {
                return new ViewModel(array(
                    'success' => true,
                    'name' => $name
                ));
            } else {
                // redirect back
                return $this->redirect()->toRoute('settings/default', array(
                    'action' => 'list'
                ));
            }
        }
        return new ViewModel(array(
            'form' => $this->mailingListService->getDeleteListForm(),
            'name' => $name
        ));
    }
}
