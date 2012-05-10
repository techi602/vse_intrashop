<?php

class Service_PerformanceCreditsAdmin
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEmployeeList($superiorEmployeeUserId)
    {
        $query = $this->em->createQuery("
            SELECT e
            FROM Employee e
            JOIN e.superiorEmployee se
            WHERE se.id = ?1
        ");

        $query->setParameter(1, $superiorEmployeeUserId);

        $employees = array();

        foreach ($query->getArrayResult() as $employee) {
            $employees[] = array(
                'employeeName' => $employee['name'],
                'employeeId' => $employee['id']
            );
        }

        return $employees;
    }

    /**
     * @todo Process $note
     */
    public function givePerformanceCredits($supperiorEmployeeUserId, $employeeUserId, $creditsAmount, $note)
    {
        $this->em->beginTransaction();
        $employee = $this->em->find('Employee', $employeeUserId);
        $employee->setBalance($employee->getBalance() + $creditsAmount);
        $this->em->persist($employee);

        $supperiorEmployee = $this->em->find('SuperiorEmployee', $supperiorEmployeeUserId);
        $supperiorEmployee->setSuperiorBalance($supperiorEmployee->getSuperiorBalance() - $creditsAmount);
        $this->em->persist($supperiorEmployee);

        $this->em->commit();
        $this->em->flush();
    }

}
