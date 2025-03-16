<?php

namespace App\enum;

enum InvoiceStatusEnum: int
{
    case ISSUED = 1;
    CASE OVERDUE = 2;
    case CANCELED = 3;
    case PAID = 4;
}
