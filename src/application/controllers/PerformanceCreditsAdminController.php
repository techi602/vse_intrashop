<?php

class PerformanceCreditsAdminController extends Controller_Default
{

    /** @var Service_PerformanceCreditsAdmin */
    private $performanceCreditsAdminService;

    /** @var Service_Employee */
    private $employeeService;

    public function init()
    {
        parent::init();

        if (!$this->loggedSuperiorEmployee) {
            throw new Exception("Unauthorized");
        }

        $this->performanceCreditsAdminService = new Service_PerformanceCreditsAdmin($this->em);
        $this->employeeService = new Service_Employee($this->em);
    }

    public function indexAction()
    {
        $employeeList = $this->performanceCreditsAdminService->getEmployeeList($this->loggedSuperiorEmployee->getId());
        $this->view->employeeList = $employeeList;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $employeeId = $request->getParam('userId');
        $employeeInfo = $this->employeeService->getEmployeeInfo($employeeId);

        $adminForm = new Form_CreditsAdmin(
            $this->loggedSuperiorEmployee->getSuperiorBalance(),
            'bonusových bodů'
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
                    $this->performanceCreditsAdminService->givePerformanceCredits(
                            $this->loggedSuperiorEmployee->getId(), $employeeId, $adminForm->getValue('creditsAmount'), $adminForm->getValue('note')
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
