<?php

class Service_CreditsAdmin
{
    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEmployeeList()
    {
         $query = $this->em->createQuery("
            SELECT e
            FROM Employee e
        ");

        $employees = array();

        foreach ($query->getArrayResult() as $employee) {
            $employees[] = array(
                'employeeName' => $employee['name'],
                'employeeId' => $employee['id']
            );
        }

        return $employees;
    }

    public function getEmployeeInfo($employeeUserId)
    {
        $query = $this->em->createQuery("
            SELECT e
            FROM Employee e
            WHERE e.id = ?1
        ");
        $query->setParameter(1, $employeeUserId);

        $employee = $query->getSingleResult();

        $employeeInfo = array(
            'employeeName' => $employee->getName()
        );

        return $employeeInfo;
    }

    public function getFinantialBalance() {

    }

    /**
     * @todo Process $note
     */
    public function giveExtraCredits($personnelOfficerUserId, $employeeUserId, $creditsAmount, $note)
    {
        $employee = $this->em->find('Employee', $employeeUserId);
        $employee->setBalance($employee->getBalance() + $creditsAmount);
        $this->em->persist($employee);

        $personnelOfficer = $this->em->find('PersonnelOfficer', $personnelOfficerUserId);
        $personnelOfficer->setPersonnelOfficerBalance($personnelOfficer->getPersonnelOfficerBalance() - $creditsAmount);
        $this->em->persist($personnelOfficer);

        $this->em->flush();
    }
}
