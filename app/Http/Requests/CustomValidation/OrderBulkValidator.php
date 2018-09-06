<?php

namespace App\Http\Requests\CustomValidation;

use App\Http\Utils\DateConvertor;
use App\Order;
use App\ItImport;
use App\Presell;
use App\HeightRequirement;
use App\OrderBoard;
use App\ProtectiveCover;
use DateTime;


class OrderBulkValidator
{
    public static function validate($data)
    {
        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['errors'] = [];


            if (Order::where('nsn', $data[$i]['nsn'])->first()) {
                array_push($data[$i]['errors'],
                    'An order has previously been submitted for this NSN. Please enter a new NSN or work with your Coates representative to update the order.');
            } elseif (ItImport::where('nsn', $data[$i]['nsn'])->first() == null) {
                ('Please enter a valid NSN');
                array_push($data[$i]['errors'],
                    'Please enter a valid NSN. ');
            } else {
               //return address
            }

            if (filter_var($data[$i]['presell'], FILTER_VALIDATE_INT)) {
                if ( ! Presell::where('value', $data[$i]['presell'])->first()) {
                    array_push($data[$i]['errors'],
                        'Presells value is not valid. ');
                }
            } else {
                array_push($data[$i]['errors'], 'Presells value is not valid. ');
            }

            if (filter_var($data[$i]['order_board'], FILTER_VALIDATE_INT)) {
                if ( ! OrderBoard::where('value', $data[$i]['order_board'])->first()) {
                    array_push($data[$i]['errors'],
                        'Order boards value is not valid. ');
                }
            } else {
                array_push($data[$i]['errors'], 'Order boards value is not valid. ');
            }

            if ($data[$i]['presell'] === 0 && $data[$i]['order_board'] === 0) {
                array_push($data[$i]['errors'],
                    'Please select at least 1 Single Menu Board or 1 Double Menu Board. ');
            }

//            if ( ! OrderBoard::where('value', $data[$i]['order_board'])->first()) {
//                array_push($data[$i]['errors'],
//                    'Order Board value is not valid. ');
//            }

            if ( ! ProtectiveCover::where('value', $data[$i]['protective_cover'])->first()) {
                array_push($data[$i]['errors'],
                    'Protective Cover value is not valid. ');
            }

            if ( ! HeightRequirement::where('value', $data[$i]['height_requirement'])->first()) {
                array_push($data[$i]['errors'],
                    'Height Requirements value is not valid. ');
            }

            if (strlen($data[$i]['delivery_note']) > 255) {
                array_push($data[$i]['errors'],
                    'Delivery Notes field exceeds 255 characters. ');
            }

            if (strlen($data[$i]['note']) > 255) {
                array_push($data[$i]['errors'],
                    'Notes field exceeds 255 characters. ');
            }

            $data[$i]['errors'] = array_merge($data[$i]['errors'],
                self::validateEnclosureDate(DateConvertor::toDate($data[$i]['requested_enclosure_delivery_date'])
                ));
        }

        return $data;
    }

    private static function validateEnclosureDate($date)
    {
        $errors = [];

        if ($date === false) {
            array_push($errors, "Delivery date has to be mm/dd/yyyy format");

            return $errors;
        }

        $holidays = [
            '2018-01-01',
            '2018-01-15',
            '2018-02-19',
            '2018-05-28',
            '2018-07-94',
            '2018-09-03',
            '2018-10-08',
            '2018-11-22',
            '2018-11-23',
            '2018-12-25',
        ];


        $day = date("D", strtotime($date->format('m/d/Y')));

        if (in_array($date, $holidays)) {

            array_push($errors, "Delivery cannot be scheduled on a holiday.");
        }

        if ($day == 'Sat' || $day == 'Sun') {

            array_push($errors, "Delivery date must be Monday through Friday.");
        }

        if (Order::where('requested_enclosure_delivery_date', $date->format('m/d/Y'))->count() > 36) {
            array_push($errors, "An order has previously been submitted for 
this NSN. Please enter a new NSN or work with your Coates representative to update the order.");
        }


        return $errors;

//        $dates = DB::select("SELECT Requested_Enclosure_Delivery_Date,
//                                                COUNT(*) c
//                                                FROM orders_supermaster
//                                                    GROUP BY
//                                                    Requested_Enclosure_Delivery_Date HAVING c >= 1
//                                                    AND
//                                                    Requested_Enclosure_Delivery_Date >
//                                                        CASE
//                                                            WHEN DAYOFWEEK(NOW()) = 7 OR DAYOFWEEK(NOW()) = 1 THEN 35
//                                                            WHEN DAYOFWEEK(NOW()) = 5 AND TIME_FORMAT(NOW(), '%r') > '12:00:00 PM' THEN 35
//                                                            ELSE NOW() + INTERVAL 34 DAY
//                                                        END");

    }

    public static function isValid($data)
    {
        for ($i = 0; $i < count($data); $i++) {
            if (count($data[$i]['errors'])) {
                return false;
            }
        }

        return true;
    }
}