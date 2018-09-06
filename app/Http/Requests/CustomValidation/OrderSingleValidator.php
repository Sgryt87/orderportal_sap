<?php

namespace App\Http\Requests\CustomValidation;


use App\Order;
use App\ItImport;
use Symfony\Component\HttpFoundation\Response;


class OrderSingleValidator
{
    public static function validate($data)
    {
        $data['errors'] = [];

        if (Order::where('nsn', $data['nsn'])->first()) {
            array_push($data['errors'], 'NSN was used');
        } elseif (ItImport::where('nsn', $data['nsn'])->first() == null) {
            array_push($data['errors'],
                'Please enter a valid NSN. ');
        } else {
            //return address
        }

        return response([
            'data' => $data
        ], count($data['errors']) ? Response::HTTP_BAD_REQUEST : Response::HTTP_OK);
    }
}