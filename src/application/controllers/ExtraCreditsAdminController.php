<?php

class ExtraCreditsAdminController extends Controller_Default
{
    /** @var Service_CreditsAdmin */
    private $creditsAdminService;

    public function init() {
        parent::init();

        if (!$this->loggedPersonnelOfficer) {
            throw new Exception("Unauthorized");
        }

        $this->creditsAdminService = new Service_CreditsAdmin($this->em);
    }

    public function indexAction()
    {
        $this->view->employeeList = $this->creditsAdminService->getEmployeeList();
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $employeeId = $request->getParam('userId');
        $employeeInfo = $this->creditsAdminService->getEmployeeInfo($employeeId);

        $adminForm = new Form_ExtraCreditsAdmin(
            $employeeInfo['employeeName'],
            $this->loggedPersonnelOfficer->getPersonnelOfficerBalance()
        );
        $adminForm->prepare();

        if ($this->_request->isPost()) {
            if ($this->_getParam('buttoncancel')) {
                $this->_helper->redirector->goto('index');
            }
            if ($adminForm->isValid($this->_request->getPost())) {
                $this->creditsAdminService->giveExtraCredits(
                    $this->loggedPersonnelOfficer->getId(),
                    $employeeId,
                    $adminForm->getValue('creditsAmount'),
                    $adminForm->getValue('note')
                );
                $this->_helper->redirector->goto('index');
            }
        }

        $this->view->adminForm = $adminForm;
        $this->view->employeeInfo = $employeeInfo;
    }
}
