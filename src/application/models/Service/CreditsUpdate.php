<?php

class Service_CreditsUpdate
{

    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function userExists($username)
    {
        $query = $this->em->createQuery("SELECT e FROM Employee e WHERE e.username=?1");
        $query->setParameter(1, $username);
        $foundUsers = $query->getResult();
        $foundUserCount = count($foundUsers);
        switch($foundUserCount) {
            case 0:
                return false;

            case 1:
                return true;

            default:
                throw new InvalidStateException("Found $foundUserCount users with username '$username'");
        }
    }

    public function createUser(
        $name,
        $username,
        $departmentName,
        $email,
        DateTime $employedSince,
        $supperiorEmployeeUsername,
        $employeeFunction
    ) {
        $department = $this->getDepartment($departmentName);

        if (!$department) {
            throw new Exception("Department '$departmentName' not found");
        }

        $employee = new Employee();
        $employee->setName($name);
        $employee->setUsername($username);
        $employee->setDepartment($department);
        $employee->setEmail($email);
        $employee->setEmployedSince($employedSince);
        $employee->setEmployed(true);
        $employee->setBalance(0);
        $employee->setFunction($employeeFunction);

        if ($supperiorEmployeeUsername) {
            $supperiorEmployee = $this->getSuperiorEmployee($supperiorEmployeeUsername);
            if (!$supperiorEmployee) {
                throw new Exception("Supperior employee '$supperiorEmployeeUsername' not found");
            }
            $employee->setSuperiorEmployee($supperiorEmployee);
        }

        $this->em->persist($employee);
        $this->em->flush();
    }

    public function getDepartment($name)
    {
        $query = $this->em->createQuery("SELECT d FROM Department d WHERE d.name=?1");
        $query->setParameter(1, $name);
        $foundDepartments = $query->getResult();

        switch (count($foundDepartments)) {
            case 0:
                return null;

            case 1:
                $department = array_pop($foundDepartments);
                return $department;

            default:
                throw new InvalidStateException();
        }
    }

    public function getEmplyoeeList()
    {
        $query = $this->em->createQuery("
            SELECT e
            FROM Employee e
            WHERE
                e.dismissal IS NULL
        ");

        $employees = array();

        foreach ($query->getResult() as $employee) {
            $employees[] = array(
                'id' => $employee->getId(),
                'name' => $employee->getName(),
                'userName' => $employee->getUsername(),
                'employedSince' => $employee->getEmployedSince()
            );
        }

        return $employees;
    }

    public function addCredits($employeeId, $creditsAmount)
    {
        $employee = $this->em->find('Employee', $employeeId);
        $employee->setBalance($employee->getBalance() + $creditsAmount);
        $this->em->persist($employee);
        $this->em->flush();
    }

    private function getSuperiorEmployee($username)
    {
        $query = $this->em->createQuery("SELECT se FROM SuperiorEmployee se WHERE se.username=?1");
        $query->setParameter(1, $username);
        $foundSuperiorEmployees = $query->getResult();

        switch (count($foundSuperiorEmployees)) {
            case 0:
                return null;

            case 1:
                $superiorEmployee = array_pop($foundSuperiorEmployees);
                return $superiorEmployee;

            default:
                throw new InvalidStateException();
        }
    }
}
