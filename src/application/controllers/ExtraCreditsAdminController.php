<?php

class ExtraCreditsAdminController extends Controller_Default
{

    /** @var Service_ExtraCreditsAdmin */
    private $extraCreditsAdminService;

    /** @var Service_Employee */
    private $employeeService;

    /** @var Service_Notification */
    private $notificationService;

    public function init()
    {
        parent::init();

        if (!$this->loggedPersonnelOfficer) {
            throw new Exception("Unauthorized");
        }

        $this->extraCreditsAdminService = new Service_ExtraCreditsAdmin($this->em);
        $this->employeeService = new Service_Employee($this->em);
        $this->notificationService = new Service_Notification($this->em);
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
            $this->loggedPersonnelOfficer->getPersonnelOfficerBalance(),
            'mimořádných bodů'
        );
        $adminForm->prepare();

        $showConfirmDialog = false;
        if ($this->_request->isPost()) {
            if ($this->_getParam('buttoncancel')) {
                $this->_helper->redirector->goto('index');
            }
            if ($adminForm->isValid($this->_request->getPost())) {
                $confirmed = ($this->getRequest()->getPost('hiddenconfirm') == 'true');
                $creditsAmount = $adminForm->getValue('creditsAmount');
                if ($confirmed) {
                    $note = $adminForm->getValue('note');
                    $this->extraCreditsAdminService->giveExtraCredits(
                            $this->loggedPersonnelOfficer->getId(), $employeeId, $creditsAmount, $note
                    );
                    $this->notificationService->notifyExtraCredits($employeeId, $creditsAmount, $note);
                    $this->_helper->redirector->goto('index');
                } else {
                    $showConfirmDialog = true;
                    $this->view->confirmDialogCreditAmount = $creditsAmount;
                }
            }
        }

        $this->view->showConfirmDialog = $showConfirmDialog;
        $this->view->adminForm = $adminForm;
        $this->view->employeeInfo = $employeeInfo;
    }

}
