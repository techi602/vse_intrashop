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

    public function notifyOrderReady(Employee $orderingEmployee, Order $order)
    {
        $recipientEmailAddress = $orderingEmployee->getEmail();
        $subject = "Objednávka připravena k vyzvednutí";

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
        $subject = "Storno objednávky";

        $body = "";
        $body .= "Objednávka ze dne " . date("j. n. Y") . " s následujícím objednaným zbožím byla stornována:\n";
        $body .= "  {$order->getProductVariant()->getProduct()->getName()}\n";
        $body .= "    varianta: {$order->getProductVariant()->getColor()}\n";

        if ($reason) {
            $body .= "\nDůvod storna: $reason\n";
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

        file_put_contents(APPLICATION_PATH . "/../public/emails.txt", $s, FILE_APPEND);
    }
}
