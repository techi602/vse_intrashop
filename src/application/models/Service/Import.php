<?php

class Service_Import
{

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct()
    {
        $this->em = Zend_Registry::get('EntityManager');
    }

    private function truncateTable($em, $className)
    {
        $cmd = $em->getClassMetadata($className);
        $connection = $em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->executeUpdate($dbPlatform->getTruncateTableSql('products_categories'));
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

    private function truncate($em)
    {
        /**
          $classes = $em->getMetadataFactory()->getAllMetadata();

          foreach ($classes as $className) {
          $this->truncateTable($em, $className);
          }
         * 
         */
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $entities = array(
            'ProductColor',
            'ProductSize',
            'ProductAvailability',
            'ProductVariant',
            'Product',
            'Category',
            'OrderStatus',
            'Employee',
            'Order',
            'ProductAvailability',
            'Department',
            'SuperiorEmployee',
            'WarehouseKeeper',
            'PersonnelOfficer');

        $classes = array();
        foreach ($entities as $entity) {
            $classes[] = $em->getClassMetadata($entity);
        }

        $tool->dropSchema($classes);
        $tool->createSchema($classes);



        /*
          $this->truncateTable($em, 'ProductColor');
          $this->truncateTable($em, 'ProductSize');
          $this->truncateTable($em, 'ProductAvailability');
          $this->truncateTable($em, 'ProductVariant');
          $this->truncateTable($em, 'Product');
          $this->truncateTable($em, 'Category');
          $this->truncateTable($em, 'OrderStatus');
          $this->truncateTable($em, 'Employee');
          $this->truncateTable($em, 'Order');
         */
    }

    public function import()
    {
        $this->truncate($this->em);


        $em = $this->em;

        $cat1 = new Category();
        $cat1->setName("Móda");
        $em->persist($cat1);

        $cat2 = new Category();
        $cat2->setName("Oblečení");
        $cat2->setParentCategory($cat1);
        $em->persist($cat2);

        $cat3 = new Category();
        $cat3->setName("Outdoor");
        $em->persist($cat3);

        $cat4 = new Category();
        $cat4->setName("Poukázky");
        $em->persist($cat4);

        $colorWhite = new ProductColor();
        $colorWhite->setName('Bílá');
        $em->persist($colorWhite);

        $colorBlue = new ProductColor();
        $colorBlue->setName('Modrá');
        $em->persist($colorBlue);

        $colorRed = new ProductColor();
        $colorRed->setName('Červená');
        $em->persist($colorRed);


        $availStock = new ProductAvailability();
        $availStock->setName("skladem");
        $availStock->setAvailable(true);
        $availStock->setDays(0);
        $em->persist($availStock);



        $p1 = new Product();
        $p1->setName("Dárková poukázka");
        $p1->setDescription("Poukázka na nákup zboží");
        $p1->setPrice(5000);
        $p1->setCredits(5000);
        $p1->setCategories(array($cat4));
        $p1->setVisible(true);
        $p1->setMultipleVariants(false);
        $p1->setPicture(base64_encode(file_get_contents("http://www.poukazky.cz/_dataPublic/shopItems/1bbba875360982c4c668693ca68826c0/Firotour5000.JPG")));
        $p1->setCode("DP5000");
        $em->persist($p1);

        $v1 = new ProductVariant();
        $v1->setName("Dárková poukázka");
        $v1->setProduct($p1);
        $v1->setQuantity(10);
        $v1->setAvailability($availStock);
        $em->persist($v1);

        $product = new Product();
        $product->setName("Deštník");
        $product->setDescription("Deštník proti dešti");
        $product->setPicture(base64_encode(file_get_contents("http://4.bp.blogspot.com/-V2eN7qTt9wE/TvOTH5zxhyI/AAAAAAAABlE/poiQTfz-5PA/s1600/destnik_cerveny.jpg")));
        $product->setCredits(200);
        $product->setPrice(200);
        $product->setCategories(array($cat3));
        $product->setVisible(true);
        $product->setCode("AB01");
        $product->setMultipleVariants(true);
        $em->persist($product);

        $variant = new ProductVariant();
        $variant->setName("Červený deštník");
        $variant->setProduct($product);
        $variant->setColor($colorRed);
        $variant->setQuantity(3);
        $variant->setAvailability($availStock);
        $em->persist($variant);

        $variant = new ProductVariant();
        $variant->setName("Modrý deštník");
        $variant->setProduct($product);
        $variant->setColor($colorBlue);
        $variant->setQuantity(4);
        $variant->setAvailability($availStock);
        $em->persist($variant);

        $boss = new SuperiorEmployee();
        $boss->setName("Johan Opršálek");
        $boss->setEmail("admin@intrashop");
        $boss->setUsername("ioprj01");
        $boss->setEmployedSince(new DateTime("1990-05-06"));
        $boss->setBalance(65655);
        $boss->setEmployed(true);
        $boss->setFunction("Vedoucí oddělení");
        $boss->setSuperiorBalance(17000);
        $em->persist($boss);

        $dpt1 = new Department();
        $dpt1->setName("Nákup");
        $dpt1->setBoss($boss);
        $em->persist($dpt1);

        $janNovakEmployee = new Employee();
        $janNovakEmployee->setName('Jan Novák');
        $janNovakEmployee->setEmail("jan.novak@intrashop");
        $janNovakEmployee->setEmployedSince(new DateTime("2000-03-01"));
        $janNovakEmployee->setBalance(5000);
        $janNovakEmployee->setUsername('inovj01');
        $janNovakEmployee->setEmployed(true);
        $janNovakEmployee->setDepartment($dpt1);
        $janNovakEmployee->setFunction("Řadový zaměstnanec");
        $janNovakEmployee->setSuperiorEmployee($boss);
        $em->persist($janNovakEmployee);

        $personnel = new PersonnelOfficer();
        $personnel->setPersonnelOfficerBalance(70000);
        $personnel->setName('Petr Holub');
        $personnel->setEmail('personnel@intrashop');
        $personnel->setEmployedSince(DateTime::createFromFormat('U', mktime(12, 0, 0, date('m'), date('d'), 2008)));
        $personnel->setBalance(25000);
        $personnel->setUsername('iholp01');
        $personnel->setEmployed(true);
        $personnel->setDepartment($dpt1);
        $personnel->setFunction("PO");
        $em->persist($personnel);

        $warehouser = new WarehouseKeeper();
        $warehouser->setName('Stanislav Sekanina');
        $warehouser->setEmail('warehouser@intrashop');
        $warehouser->setEmployedSince(new DateTime("1995-03-01"));
        $warehouser->setBalance(35000);
        $warehouser->setUsername('iseks01');
        $warehouser->setEmployed(true);
        $warehouser->setDepartment($dpt1);
        $warehouser->setFunction("Skladník");
        $em->persist($warehouser);

        $status1 = new OrderStatus();
        $status1->setName("Nová");
        $status1->setCode(OrderStatus::STATUS_NEW);
        $em->persist($status1);

        $status2 = new OrderStatus();
        $status2->setName("Vyřízená");
        $status2->setCode(OrderStatus::STATUS_CONFIRMED);
        $em->persist($status2);

        $status3 = new OrderStatus();
        $status3->setName("Stornovaná");
        $status3->setCode(OrderStatus::STATUS_STORNO);
        $em->persist($status3);

        $order = new Order();
        $order->setEmployee($janNovakEmployee);
        $order->setInserted(new DateTime());
        $order->setProductVariant($variant);
        $order->setCredits($variant->getProduct()->getCredits());
        $order->setStatus($status1);
        $order->setAmount(1);
        $order->setNote("testovací objednávka");
        $em->persist($order);

        $em->flush();

        $emailFile = fopen(APPLICATION_PATH . "/../public/emails.txt", "w");
        fwrite($emailFile, "~~~\n");
        fclose($emailFile);
    }

}