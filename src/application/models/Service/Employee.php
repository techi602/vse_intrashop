<?php

class Service_Employee
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
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
            'employeeName' => $employee->getName(),
            'functionName' => $employee->getFunction(),
            'departmentName' => $employee->getDepartment()->getName()
        );

        return $employeeInfo;
    }

}
