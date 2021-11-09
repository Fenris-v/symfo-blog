<?php

namespace App\Enums;

enum Subscription: string
{
    case Free = 'free';
    case Plus = 'plus';
    case Pro = 'pro';
}
