<?php

namespace App\Http\Enums;

enum TableLocation: string
{
    case IN_FIRST_FLOOR = 'Main Floor';
    case OUT_FIRST_FLOOR = 'Outside Main Floor';
    case IN_SECOND_FLOOR = 'Second Floor';
    case TERRACE = 'Terrace';

}