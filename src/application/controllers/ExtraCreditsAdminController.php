<?php

class ExtraCreditsAdminController extends Controller_Default
{

    /** @var Service_ExtraCreditsAdmin */
    private $extraCreditsAdminService;

    /** @var Service_Employee */
    private $employeeService;

    public function init()
    {
        parent::init();

        if (!$this->loggedPersonnelOfficer) {
            throw new Exception("Unauthorized");
        }

        $this->extraCreditsAdminService = new Service_ExtraCreditsAdmin($this->em);
        $this->employeeService = new Service_Employee($this->em);
    }

    public function indexAction()
    {
        $this->view->employeeList = $this->extraCreditsAdminService->getEmployeeList();
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $employeeId = $request->getParam('userId');
        $employeeInfo = $this->employeeService->getEmployeeInfo($employeeId);

        $adminForm = new Form_CreditsAdmin(
                        $this->loggedPersonnelOfficer->getPersonnelOfficerBalance()
        );
        $adminForm->prepare();

        $showConfirmDialog = false;
        if ($this->_request->isPost()) {
            if ($this->_getParam('buttoncancel')) {
                $this->_helper->redirector->goto('index');
            }
            if ($adminForm->isValid($this->_request->getPost())) {
                $confirmed = ($this->getRequest()->getPost('hiddenconfirm') == 'true');
                if ($confirmed) {
                    $this->extraCreditsAdminService->giveExtraCredits(
                            $this->loggedPersonnelOfficer->getId(), $employeeId, $adminForm->getValue('creditsAmount'), $adminForm->getValue('note')
                    );
                    $this->_helper->redirector->goto('index');
                } else {
                    $showConfirmDialog = true;
                }
            }
        }

        $this->view->showConfirmDialog = $showConfirmDialog;
        $this->view->adminForm = $adminForm;
        $this->view->employeeInfo = $employeeInfo;
    }

}
