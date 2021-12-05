<?php

namespace App\Enums;

enum Subscription: string
{
    case Free = 'free';
    case Plus = 'plus';
    case Pro = 'pro';

    case TimeOfRestriction = '-1 hour';
}
