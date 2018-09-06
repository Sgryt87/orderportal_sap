<?php

namespace App\Http\Utils;
use DateTime;
use Carbon\Carbon;


class DateConvertor
{
    public static function toDate($date)
    {
//        return Carbon::createFromFormat('m/d/Y', $date);
        return date('Y-m-d', strtotime($date));
    }

}
//
//$order['requested_enclosure_delivery_date'] = DateTime::createFromFormat('m/d/Y',
//    $data[$i]['requested_enclosure_delivery_date'])->format('Y-m-d');