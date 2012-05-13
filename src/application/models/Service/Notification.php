<?php

class Service_Notification
{
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
