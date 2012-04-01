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
        
        
        $this->truncateTable($em, 'ProductColor');
        $this->truncateTable($em, 'ProductSize');
        $this->truncateTable($em, 'ProductAvailability');
        $this->truncateTable($em, 'ProductVariant');
        $this->truncateTable($em, 'Product');
        $this->truncateTable($em, 'Category');
        $this->truncateTable($em, 'OrderStatus');
        $this->truncateTable($em, 'Employee');
        $this->truncateTable($em, 'Order');
        
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
        
        $product = new Product();
        $product->setName("Deštník");
        $product->setDescription("Deštník do deště");
        $product->setPicture("http://4.bp.blogspot.com/-V2eN7qTt9wE/TvOTH5zxhyI/AAAAAAAABlE/poiQTfz-5PA/s1600/destnik_cerveny.jpg");
        $product->setCredits(200);
        $product->setPrice(200);
        $product->setCategories(array($cat3));
        $em->persist($product);
        
        $variant = new ProductVariant();
        $variant->setName("Červený deštník");
        $variant->setProduct($product);
        $variant->setColor($colorRed);
        $variant->setQuantity(3);
        $variant->setAvailability($availStock);
        $em->persist($variant);
                

        $employee = new Employee();
        $employee->setName('Jan Novák');
        $employee->setEmail("jan.novak@localhost");
        $employee->setEmployedSince(new DateTime("2000-03-01"));
        $employee->setBalance(5000);
        $em->persist($employee);
        
        
        $status1 = new OrderStatus();
        $status1->setName("Nová");
        $em->persist($status1);
        
        $status2 = new OrderStatus();
        $status2->setName("Vyřízená");
        $em->persist($status2);
        
        $order = new Order();
        $order->setEmployee($employee);
        $order->setInserted(new DateTime());
        $order->setProductVariant($variant);
        $order->setCredits($variant->getProduct()->getCredits());
        $order->setStatus($status1);
        $em->persist($order);

        
        $em->flush();
    }
    
    
}