<?php

namespace App\Services;

use App\Exceptions\AppException; // create this if you don't already have it
use App\DTO\NotificationDTO;
use App\DTO\RecipientsContactInfoDTO;
use App\Constants\AppConstants; // whatever you used before
use Illuminate\Support\Facades\DB;
use Throwable;

class NotificationService
{
    private int $rowsPerPage;

    public function __construct()
    {
        $this->rowsPerPage = AppConstants::ROWS_PER_PAGE;
    }

    /**
     * Keep signature same as original: $recipient, $groupRecipient, Paginator $paginator, $pageDirection
     * Returns array of NotificationDTO
     */
    public function searchRecords($recipient, $groupRecipient, $paginator, $pageDirection): array
    {
        $notifications = [];
        try {
            $searchRecipient = $recipient ?: 'none';
            $groupRecipient = $groupRecipient ?: 'none';

            $whereParts = [];
            if ($searchRecipient) {
                $whereParts[] = "(d.recipient_id_or_group = :recipient OR d.recipient_id_or_group = :group_recipient)";
            }

            $whereClause = $whereParts ? implode(' AND ', $whereParts) : '1=1';

            // count total rows
            $countSql = "SELECT COUNT(d.id) AS totalRows FROM notification d WHERE {$whereClause}";
            $bindings = [
                'recipient' => $searchRecipient,
                'group_recipient' => $groupRecipient,
            ];
            $totalRowsRow = DB::selectOne($countSql, $bindings);
            $totalRows = $totalRowsRow ? (int)$totalRowsRow->totalRows : 0;

            $paginator->setTotalRows($totalRows);
            $paginator->setRowsPerPage($this->rowsPerPage);

            switch (strtoupper((string)$pageDirection)) {
                case 'FIRST':
                    $paginator->pageFirst();
                    break;
                case 'PREVIOUS':
                    $paginator->pagePrevious();
                    break;
                case 'NEXT':
                    $paginator->pageNext();
                    break;
                case 'LAST':
                    $paginator->pageLast();
                    break;
                default:
                    $paginator->pageFirst();
                    break;
            }

            $limitStartRow = (int)$paginator->getStartRow();

            $sql = "SELECT d.id,d.sender_id,d.recipient_id_or_group,d.notification_subject,d.notification_message
                    ,DATE_FORMAT(d.created, '%e-%b-%Y %h:%i %p') AS date_sent
                    ,d.guid
                    ,CONCAT_WS(' ', u.first_name, u.last_name) AS _sender_name
                    ,o.organization_name AS _sender_organization
                    FROM notification d
                    LEFT JOIN users u ON d.sender_id = u.id
                    LEFT JOIN organization o ON u.organization_id = o.id
                    WHERE {$whereClause}
                    ORDER BY d.created DESC
                    LIMIT {$limitStartRow}, {$this->rowsPerPage}";

            $records = DB::select($sql, $bindings);

            if ($records) {
                $displaySerialNo = $paginator->getStartRow() + 1;
                foreach ($records as $rec) {
                    $dto = new NotificationDTO();
                    $dto->id = $rec->id;
                    $dto->sender_id = $rec->sender_id;
                    $dto->sender_name = $rec->_sender_name ?? null;
                    $dto->sender_organization = $rec->_sender_organization ?? null;
                    $dto->subject = $rec->notification_subject;
                    $dto->message = $rec->notification_message;
                    $dto->created = $rec->date_sent;
                    $dto->guid = $rec->guid;
                    $dto->displaySerialNo = $displaySerialNo++;
                    $notifications[] = $dto;
                }
            }
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }

        return $notifications;
    }

    public function getUserMessages($userProfileId): ?array
    {
        $notifications = [];
        try {
            $sql = "SELECT user.profile_type,user.primary_role,user.primary_phone,user.email_address
                    ,organization.level_of_government
                    FROM users user
                    JOIN organization organization ON user.organization_id = organization.id
                    WHERE user.id = :recipientId";
            $recipientDetails = DB::selectOne($sql, ['recipientId' => $userProfileId]);

            if (! $recipientDetails) {
                return $notifications;
            }

            $profileType = $recipientDetails->profile_type;
            $organizationLevelOfGovernment = $recipientDetails->level_of_government;

            $possibleRecipientOrGroupValues = [$userProfileId];
            if ($profileType == AppConstants::MDA_USER_PROFILE) {
                if ($organizationLevelOfGovernment == AppConstants::FEDERAL) {
                    $possibleRecipientOrGroupValues[] = AppConstants::FEDERAL_MDA;
                }
            }

            $possibleRecipientValueList = implode(',', array_map(function ($v) {
                return DB::getPdo()->quote((string)$v);
            }, $possibleRecipientOrGroupValues));

            $sql = "SELECT d.id,d.sender_id,d.recipient_id_or_group,d.notification_subject,d.notification_message
                    ,DATE_FORMAT(d.created, '%d/%m/%y') AS date_sent
                    ,d.guid
                    ,CONCAT_WS(' ', u.first_name, u.last_name) AS _sender_name
                    ,o.organization_name AS _sender_organization
                    FROM notification d
                    LEFT JOIN users u ON d.sender_id = u.id
                    LEFT JOIN organization o ON u.organization_id = o.id
                    WHERE d.recipient_id_or_group IN ({$possibleRecipientValueList})
                    ORDER BY d.created DESC";

            $records = DB::select($sql);

            if ($records) {
                $displaySerialNo = 1;
                foreach ($records as $rec) {
                    $dto = new NotificationDTO();
                    $dto->id = $rec->id;
                    $dto->sender_id = $rec->sender_id;
                    $dto->sender_name = $rec->_sender_name ?? null;
                    $dto->sender_organization = $rec->_sender_organization ?? null;
                    $dto->subject = $rec->notification_subject;
                    // leaving message out as original commented it out
                    $dto->created = $rec->date_sent;
                    $dto->guid = $rec->guid;
                    $dto->displaySerialNo = $displaySerialNo++;
                    $notifications[] = $dto;
                }
            }
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }

        return $notifications;
    }

    public function getTotalUserUnreadMessage($userProfileId): ?int
    {
        $totalUnreadMessages = 0;
        try {
            $sql = "SELECT user.profile_type,user.primary_role,user.primary_phone,user.email_address
                    ,organization.level_of_government
                    FROM users user
                    JOIN organization organization ON user.organization_id = organization.id
                    WHERE user.id = :recipientId";
            $recipientDetails = DB::selectOne($sql, ['recipientId' => $userProfileId]);

            if (! $recipientDetails) {
                return 0;
            }

            $profileType = $recipientDetails->profile_type;
            $organizationLevelOfGovernment = $recipientDetails->level_of_government;

            $possibleRecipientOrGroupValues = [$userProfileId];
            if ($profileType == AppConstants::MDA_USER_PROFILE) {
                if ($organizationLevelOfGovernment == AppConstants::FEDERAL) {
                    $possibleRecipientOrGroupValues[] = AppConstants::FEDERAL_MDA;
                }
            }

            $possibleRecipientValueList = implode(',', array_map(function ($v) {
                return DB::getPdo()->quote((string)$v);
            }, $possibleRecipientOrGroupValues));

            $sql = "SELECT d.id FROM notification d WHERE d.recipient_id_or_group IN ({$possibleRecipientValueList})";
            $receivedRows = DB::select($sql);
            $idsOfReceivedMessages = array_map(fn($r) => $r->id, $receivedRows);

            if ($idsOfReceivedMessages) {
                $sqlRead = "SELECT notification_id FROM notifications_read_by_user WHERE user_profile_id = :user_profile_id";
                $readRows = DB::select($sqlRead, ['user_profile_id' => $userProfileId]);
                $idsOfReadMessages = array_map(fn($r) => $r->notification_id, $readRows);

                if (!$idsOfReadMessages) {
                    $totalUnreadMessages = count($idsOfReceivedMessages);
                } else {
                    $idsOfUnreadMessages = array_diff($idsOfReceivedMessages, $idsOfReadMessages);
                    if ($idsOfUnreadMessages) {
                        $totalUnreadMessages = count($idsOfUnreadMessages);
                    }
                }
            }
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }

        return $totalUnreadMessages;
    }

    public function addNotification(NotificationDTO $notification): bool
    {
        try {
            $sql = "INSERT INTO notification 
                (sender_id,recipient_id_or_group,recipient_email_addresses,recipient_phone_numbers
                ,notification_subject,notification_message,sms_notification_message,created,created_by,guid)
                VALUES
                (:sender_id,:recipient_id_or_group,:recipient_email_addresses,:recipient_phone_numbers,
                :notification_subject,:notification_message,:sms_notification_message,:created,:created_by,:guid)";

            $bindings = [
                'sender_id' => $notification->sender_id,
                'recipient_id_or_group' => $notification->recipient_id_or_group,
                'recipient_email_addresses' => $notification->recipient_email_addresses,
                'recipient_phone_numbers' => $notification->recipient_phone_numbers,
                'notification_subject' => $notification->subject,
                'notification_message' => $notification->message,
                'sms_notification_message' => $notification->sms_notification_message,
                'created' => $notification->created,
                'created_by' => $notification->created_by,
                'guid' => $notification->guid,
            ];

            return DB::insert($sql, $bindings);
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }
    }

    public function updateNotificationSendStatus(NotificationDTO $notification): bool
    {
        try {
            $sql = "UPDATE notification SET
                    email_send_status = :email_send_status,
                    sms_send_status = :sms_send_status
                    WHERE guid = :guid";

            return (bool) DB::update($sql, [
                'email_send_status' => $notification->email_send_status,
                'sms_send_status' => $notification->sms_send_status,
                'guid' => $notification->guid,
            ]);
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }
    }

    public function updateDeliveryStatus(NotificationDTO $notification): bool
    {
        try {
            $sql = "UPDATE notification SET
                    email_delivery_status = :email_delivery_status,
                    sms_delivery_status = :sms_delivery_status
                    WHERE guid = :guid";

            return (bool) DB::update($sql, [
                'email_delivery_status' => $notification->email_delivery_status,
                'sms_delivery_status' => $notification->sms_delivery_status,
                'guid' => $notification->guid,
            ]);
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }
    }

    public function getNotification($guid): ?NotificationDTO
    {
        try {
            $sql = "SELECT d.id,d.sender_id,d.recipient_id_or_group,d.notification_subject,d.notification_message
                    ,DATE_FORMAT(d.created, '%D %b, %Y %h:%i %p') AS date_sent
                    ,d.guid
                    ,CONCAT_WS(' ', u.first_name, u.last_name) AS _sender_name
                    ,o.organization_name AS _sender_organization
                    FROM notification d
                    LEFT JOIN users u ON d.sender_id = u.id
                    LEFT JOIN organization o ON u.organization_id = o.id
                    WHERE d.guid = :guid";

            $rec = DB::selectOne($sql, ['guid' => $guid]);

            if ($rec) {
                $dto = new NotificationDTO();
                $dto->id = $rec->id;
                $dto->sender_id = $rec->sender_id;
                $dto->sender_name = $rec->_sender_name ?? null;
                $dto->sender_organization = $rec->_sender_organization ?? null;
                $dto->subject = $rec->notification_subject;
                $dto->message = $rec->notification_message;
                $dto->created = $rec->date_sent;
                $dto->guid = $rec->guid;
                return $dto;
            }

            return null;
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }
    }

    public function saveUserReadNotification($userProfileId, $notificationId, $dateRead): bool
    {
        try {
            // MySQL: insert ignore is used in original. We'll use INSERT ... ON DUPLICATE KEY UPDATE trick.
            // But if your table (notifications_read_by_user) has composite PK (user_profile_id, notification_id),
            // then the following works to avoid duplicate.
            $sql = "INSERT IGNORE INTO notifications_read_by_user (user_profile_id, notification_id, created)
                    VALUES (:user_profile_id, :notification_id, :created)";

            DB::statement($sql, [
                'user_profile_id' => $userProfileId,
                'notification_id' => $notificationId,
                'created' => $dateRead,
            ]);

            return true;
        } catch (Throwable $e) {
            throw new AppException($e->getMessage());
        }
    }

    public function getRecipientContactInfoByRole(array $recipientRoles)
    {
        try {
            $inString = implode(',', array_map(fn($r) => DB::getPdo()->quote((string)$r), $recipientRoles));

            $sql = "SELECT u.primary_phone, u.email_address
                    FROM users u
                    WHERE u.primary_role IN (
                        SELECT p.role_id FROM system_role_privileges p WHERE p.privilege_id IN ({$inString})
                    ) AND (u.primary_phone IS NOT NULL) AND u.record_status = 'ACTIVE'";

            $contactInfo = DB::select($sql);

            return $contactInfo; // array of stdClass objects with primary_phone and email_address
        } catch (Throwable $t) {
            throw new AppException($t->getMessage());
        }
    }

    public function getMDAContactInformationByLevelOfGovernment($levelOfGovernment): ?RecipientsContactInfoDTO
    {
        $recipientsContactInfo = new RecipientsContactInfoDTO();
        try {
            $sql = "SELECT recipient.first_name, recipient.last_name, recipient.primary_phone AS contact_phone,
                           recipient.email_address AS contact_email,
                           CONCAT(recipient.first_name,' ',recipient.last_name, ' (' , organization.organization_name, ')') AS contact_name
                    FROM users AS recipient
                    LEFT JOIN organization AS organization ON recipient.organization_id = organization.id
                    WHERE recipient.profile_type = :profile_type AND organization.level_of_government = :level_of_government";

            $records = DB::select($sql, [
                'profile_type' => AppConstants::MDA_USER_PROFILE,
                'level_of_government' => $levelOfGovernment,
            ]);

            if ($records) {
                $emails = [];
                $phones = [];
                foreach ($records as $r) {
                    if ($r->contact_email) {
                        $emails[] = [
                            'contact_email' => $r->contact_email,
                            'contact_name' => $r->contact_name,
                        ];
                    }
                    if ($r->contact_phone) {
                        $phones[] = $r->contact_phone;
                    }
                }

                if ($emails) {
                    $recipientsContactInfo->recipientsEmailAddresses = $emails;
                }
                if ($phones) {
                    $recipientsContactInfo->recipientsPhoneNumbers = implode(',', $phones);
                }
            }

            return $recipientsContactInfo;
        } catch (Throwable $t) {
            throw new AppException($t->getMessage());
        }
    }

    public function getTestingRecipientsContactInfo(): ?RecipientsContactInfoDTO
    {
        $recipientsContactInfo = new RecipientsContactInfoDTO();
        try {
            $sql = "SELECT recipient.first_name, recipient.last_name, recipient.primary_phone AS contact_phone,
                           recipient.email_address AS contact_email,
                           CONCAT(recipient.first_name,' ',recipient.last_name, ' (' , organization.organization_name, ')') AS contact_name
                    FROM `_test_notification_recipients_user_profile` AS recipient
                    LEFT JOIN organization AS organization ON recipient.organization_id = organization.id
                    WHERE recipient.id <> 333";

            $records = DB::select($sql);

            if ($records) {
                $emails = [];
                $phones = [];
                foreach ($records as $r) {
                    $emails[] = [
                        'contact_email' => $r->contact_email,
                        'contact_name' => $r->contact_name,
                    ];
                    $phones[] = $r->contact_phone;
                }

                $recipientsContactInfo->recipientsEmailAddresses = $emails;
                $recipientsContactInfo->recipientsPhoneNumbers = implode(',', $phones);
            }

            return $recipientsContactInfo;
        } catch (Throwable $t) {
            throw new AppException($t->getMessage());
        }
    }
}
