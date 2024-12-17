<?php

namespace App\Services;

enum OrderStatusEnum: string
{
    case InProgress = 'in_progress';
    case Delivering = 'delivering';
    case Delivered = 'delivered';
    case Canceled = 'canceled';
}
