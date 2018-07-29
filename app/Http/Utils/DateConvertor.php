<?php

namespace App\Http\Utils;
use DateTime;


class DateConvertor
{
    public static function toDate($date)
    {
//        return DateTime::createFromFormat('m/d/Y', $date);
        return date('Y-m-d', strtotime($date));
    }

}