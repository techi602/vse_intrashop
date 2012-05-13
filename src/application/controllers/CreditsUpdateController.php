<?php

class CreditsUpdateController extends Controller_Default
{
    /** @var Service_CreditsUpdate */
    private $creditsUpdateService;

    /** @var Service_Notification */
    private $notificationService;

    private $newEmployeesUserNames = array();

    public function init() {
        parent::init();
        //TODO: Needs to be run locally via cron only!
        $this->creditsUpdateService = new Service_CreditsUpdate($this->em);
        $this->notificationService = new Service_Notification($this->em);
    }

    public function indexAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $sourceUrl = $this->getRequest()->getParam('source');

        header('Content-type: text/plain; charset=UTF-8');
        ob_end_flush();


        $this->printInfo("LOADING DATA\n============\n");
        $sourceData = $this->loadSource($sourceUrl);

        $this->printInfo("\nPARSING DATA\n============\n");
        $parsedSourceData = $this->parseSourceData($sourceData);

        $this->printInfo("\nIMPORTING USERS\n====================\n");
        $this->importUsers($parsedSourceData);

        $this->printInfo("\nUPDATING CREDITS\n====================\n");
        $this->updateCredits();

        $this->printInfo("\n~~~\n");
    }

    private function loadSource($path)
    {
        $file = fopen($path, 'r');

        $lines = array();

        while ($line = fgetcsv($file)) {
            $lines[] = $line;
            $this->printInfo(implode(", ", $line) . "\n");
        }

        return $lines;
    }

    private function parseSourceData($data)
    {
        $parsedData = array();

        foreach ($data as $i) {
            list(
                $employeeName,
                $employeeUsername,
                $departmentName,
                $employeeEmail,
                $employedSince,
                $supperiorEmployeeUsername,
                $employeeFunction
            ) = $i;

            $pardedItem = compact('employeeName', 'employeeUsername', 'departmentName', 'employeeEmail', 'employedSince', 'supperiorEmployeeUsername', 'employeeFunction');
            $pardedItem['employedSince'] = DateTime::createFromFormat('Y-m-d', $pardedItem['employedSince']);
            $parsedData[] = $pardedItem;
        }

        foreach ($parsedData as $i) {
            $this->printInfo("{$i['employeeName']}\n");
            $this->printInfo("\temail: {$i['employeeEmail']}\n");
            $this->printInfo("\tusername: {$i['employeeUsername']}\n");
            $this->printInfo("\tdepartment: {$i['departmentName']}\n");
            $this->printInfo("\tfunction: {$i['employeeFunction']}\n");
            $this->printInfo("\temployedSince: " . $i['employedSince']->format('Y-m-d') . "\n");
            $this->printInfo("\tsuperiorEmployee: {$i['supperiorEmployeeUsername']}\n");
            $this->printInfo("\n");
        }

        return $parsedData;
    }

    private function importUsers($data)
    {
        foreach ($data as $i) {
            $employeeName = $i['employeeName'];
            $employeeUsername = $i['employeeUsername'];
            $this->printInfo("{$employeeName}($employeeUsername)...");

            if ($this->creditsUpdateService->userExists($employeeUsername)) {
                $this->printInfo(" alredy exists\n");
                $this->printError("User {$employeeUsername} already exists");
            }
            else {
                try {
                    $this->creditsUpdateService->createUser(
                        $employeeName,
                        $i['employeeUsername'],
                        $i['departmentName'],
                        $i['employeeEmail'],
                        $i['employedSince'],
                        $i['supperiorEmployeeUsername'],
                        $i['employeeFunction']
                    );
                    $this->newEmployeesUserNames[] = $i['employeeUsername'];
                    $this->printInfo(" imported\n");
                }
                catch (Exception $e) {
                    $this->printInfo(" error\n");
                    $this->printError($e->getMessage());
                }
            }
        }
    }

    private function updateCredits()
    {
        $employees = $this->creditsUpdateService->getEmplyoeeList();

        foreach ($employees as $employee) {
            $this->printInfo("{$employee['name']} ({$employee['userName']}/{$employee['employedSince']->format('Y-m-d')}):");

            $newEmployee = in_array($employee['userName'], $this->newEmployeesUserNames);

            if ($newEmployee) {
                $this->printInfo(" new");
            }
            else {
                $updateInDay = $this->countUpdateInDays($employee['employedSince']);
                $this->printInfo(" in " . ($updateInDay > 0 ? "+{$updateInDay}" : $updateInDay) . " day" . (($updateInDay == 1) ? "" : "s"));
            }

            if ($updateInDay == 0 || $newEmployee) {
                if ($newEmployee) {
                    $creditAmount = $this->getNewEmployeeCreditAmount();
                }
                else {
                    $creditAmount = $this->countCreditAmount($employee['employedSince']);
                }

                $this->printInfo(" -> adding {$creditAmount} credits");
                $this->creditsUpdateService->addCredits($employee['id'], $creditAmount);
                $this->notificationService->notifyCreditsUpdate($employee['id'], $creditAmount);
            }
            $this->printInfo("\n");
        }
    }

    private function countUpdateInDays(DateTime $employedSince)
    {
        $employedSinceTimestamp = $employedSince->format('U');

        $updateDay = (int)date('j', $employedSinceTimestamp);
        $updateMonth = (int)date('n', $employedSinceTimestamp);

        if ($updateMonth == 2 && $updateDay == 29) {
            $updateDay = 28;
        }

        $nowYearDay = (int)date('z');
        $updateYearDay = date('z', mktime(0, 0, 0, $updateMonth, $updateDay));

        $difference = $updateYearDay - $nowYearDay;

        return $difference;
    }

    private function countCreditAmount(DateTime $employedSince)
    {
        $employedSinceYear = $employedSince->format('Y');
        $nowYear = date('Y');

        $years = $nowYear - $employedSinceYear;

        if ($years == 0) {
            throw new InvalidStateException();
        }
        if ($years <= 3) {
            return 1000;
        }
        else if ($years <= 6) {
            return 2000;
        }
        else if ($years <= 9) {
            return 3500;
        }
        else {
            return 5000;
        }
    }

    private function getNewEmployeeCreditAmount() {
        return 700;
    }

    private function printInfo($s)
    {
     //   $s = nl2br($s);
        echo($s);
//        flush();
    }

    private function printError($s)
    {
        $this->printInfo("*** ERROR *** $s\n");
    }

}
