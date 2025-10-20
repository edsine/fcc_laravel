<?php

namespace App\DTO;

class NotificationDTO
{
    public $id;
    public $sender_id;
    public $sender_name;
    public $sender_organization;
    public $recipient_id_or_group;
    public $recipient_email_addresses;
    public $recipient_phone_numbers;
    public $subject;
    public $message;
    public $sms_notification_message;
    public $created;
    public $created_by;
    public $guid;
    public $email_send_status;
    public $sms_send_status;
    public $email_delivery_status;
    public $sms_delivery_status;

    // ui-only
    public $displaySerialNo;
}
