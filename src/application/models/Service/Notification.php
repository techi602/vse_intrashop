<?php

class Service_Notification
{
    /** @var Doctrine\ORM\EntityManager */
    private $em;

    public function __construct(Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function notifyOrderSuccess(Employee $employee, ProductVariant $variant, $quantity, $note = '')
    {
        $recipientEmailAddress = $employee->getEmail();
        $subject = "Nová objednávka";

        $body = "";
        $body .= "Bylo objednáno následující zboží:\n";
        $body .= "  {$variant->getProduct()->getName()}\n";
        $body .= "    varianta: {$variant->getColor()}\n";
        $body .= "    počet: {$quantity}\n";

        $this->notify($recipientEmailAddress, $subject, $body);
    }

    public function notifyOrderReady(Order $order)
    {
        $orderingEmployee = $order->getEmployee();
        $recipientEmailAddress = $orderingEmployee->getEmail();
        $subject = "Objednávka " . $order->getOrderId() . " připravena k vyzvednutí";

        $body = "";
        $body .= "Následující objednané zboží je připraveno k vyzvednutí:\n";
        $body .= "  {$order->getProductVariant()->getProduct()->getName()}\n";
        $body .= "    varianta: {$order->getProductVariant()->getColor()}\n";

        $this->notify($recipientEmailAddress, $subject, $body);
    }
    
    public function notifyOrderConfirmed(Order $order)
    {
        $orderingEmployee = $order->getEmployee();
        $recipientEmailAddress = $orderingEmployee->getEmail();
        $subject = "Objednávka " . $order->getOrderId() . " byla vyřízena";
    
        $body = "";
        $body .= "Následující objednané zboží je připraveno k vyzvednutí:\n";
        $body .= "  {$order->getProductVariant()->getProduct()->getName()}\n";
        $body .= "    varianta: {$order->getProductVariant()->getColor()}\n";
    
        $this->notify($recipientEmailAddress, $subject, $body);
    }

    public function notifyOrderCancelled($orderId, $reason = null)
    {
        /** @var Order */
        $order = $this->em->find('Order', $orderId);

        $recipientEmailAddress = $order->getEmployee()->getEmail();
        $subject = "Storno objednávky " . $order->getOrderId();

        $body = "";
        $body .= "Objednávka ze dne " . date("j. n. Y") . " s následujícím objednaným zbožím byla stornována:\n";
        $body .= "  {$order->getProductVariant()->getProduct()->getName()}\n";
        $body .= "    varianta: {$order->getProductVariant()->getColor()}\n";

        if ($reason) {
            $body .= "\nDůvod storna: $reason\n";
        }

        $this->notify($recipientEmailAddress, $subject, $body);
    }

    public function notifyCreditsUpdate($employeeId, $creditAmount)
    {
        $employee = $this->em->find('Employee', $employeeId);

        $subject = "Přiděleny věrnostní body";
        $recipientEmailAddress = $employee->getEmail();

        $body = "";
        $body .= "Bylo vám přiděleno {$creditAmount} věrnostních bodů.";

        $this->notify($recipientEmailAddress, $subject, $body);
    }

    public function notifyExtraCredits($employeeId, $creditAmount, $note)
    {
        $employee = $this->em->find('Employee', $employeeId);

        $subject = "Přiděleny mimořádné body";
        $recipientEmailAddress = $employee->getEmail();

        $body = "";
        $body .= "Bylo vám přiděleno {$creditAmount} mimořádných bodů.\n";

        if ($note) {
            $body .= "Odůvodnění: {$note}\n";
        }

        $this->notify($recipientEmailAddress, $subject, $body);
    }

    public function notifyPerformanceCredits($employeeId, $creditAmount, $note)
    {
        $employee = $this->em->find('Employee', $employeeId);

        $subject = "Přiděleny bonusové body";
        $recipientEmailAddress = $employee->getEmail();

        $body = "";
        $body .= "Bylo vám přiděleno {$creditAmount} bonusových bodů.\n";

        if ($note) {
            $body .= "Odůvodnění: {$note}\n";
        }

        $this->notify($recipientEmailAddress, $subject, $body);
    }

    private function notify($recipientEmailAddress, $subject, $body)
    {
        $s = "";
        $s .= "Date: " . date('r') . "\n";
        $s .= "To: {$recipientEmailAddress}\n";
        $s .= "Subject: {$subject}\n";
        $s .= "Body:\n";
        $s .= $body;
        $s .= "\n~~~\n";
        
        

        file_put_contents(dirname($_SERVER['PHP_SELF']) . '/emails.txt', $s, FILE_APPEND);
        
        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($s);
        $mail->setSubject($subject);
        $mail->addTo("intrashop_sw_projekt@googlegroups.com");
        $mail->setFrom("intrashop_sw_projekt@googlegroups.com", "Intrashop");
        $mail->send();
    }
}
