<?php

class Service_Notification
{
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
