<?php

namespace App\Constants;

class Status
{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    const DONATION = 1;
    const MEMBERSHIP = 3;
    const GOAL_GIFT = 4;

    const CATEGORY_FEATURED    = 1;

    const PUBLISH = 1;
    const DRAFT = 0;

    const VISIBLE_PUBLIC = 1;
    const VISIBLE_SUPPORTER = 2;
    const VISIBLE_MEMBER = 3;

    const PIN = 1;
    const UNPIN = 0;

    const DONATION_SUCCESS = 1;
    const DONATION_FAILED = 0;

    const MONTHLY_MEMBERSHIP = 1;
    const YEARLY_MEMBERSHIP = 12;

    const NOT_MEMBER_DONATION = 0;

    const RUNNING = 1;
    const COMPLETED = 2;
}
