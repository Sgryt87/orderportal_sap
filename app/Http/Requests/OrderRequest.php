<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nsn'                               => 'required|integer',
            'presell.id'                        => 'required|integer',
            'order_board.id'                    => 'required|integer',
            'protective_cover.id'               => 'required|integer',
            'height_requirement.id'             => 'required|integer',
            'delivery_note'                     => 'required|string|max:255',
            'note'                              => 'required|string|max:255',
            'requested_enclosure_delivery_date' => 'required|date',
//            'ship_date'                         => 'date',   todo FIX date format
        ];
    }
}
