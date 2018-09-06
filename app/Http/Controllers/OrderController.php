<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
//use Illuminate\Auth\Access\Response;
use App\OrderBoard;
use App\ProtectiveCover;
use Illuminate\Http\Request;
use App\Order;
use App\ItImport;
use App\Presell;
use App\HeightRequirement;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Utils\DateConvertor;
use App\Http\Requests\CustomValidation\OrderBulkValidator;
use DateTime;

use App\Http\Requests\CustomValidation\OrderSingleValidator;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page     = $request->query('page');
        $per_page = $request->query('per_page');

        $total_rows = Order::count();
        $skip       = ($page - 1) * $per_page;
        $take       = $per_page;

        $orders = Order::with([
            'presell',
            'protective_cover',
            'order_board',
            'height_requirement'
        ])->skip($skip)->take($take)->get();

        return response([
            'data'        => $orders,
            'total_rows'  => $total_rows,
            'total_pages' => round($total_rows / $per_page),
            'per_page'    => $per_page,
            'page'        => $page
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateSingle(Request $request)
    {
        return OrderSingleValidator::validate($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {

        $order = new Order();

        $order->nsn                               = $request->input('nsn');
        $order->presell_id                        = $request->input('presell.id');
        $order->order_board_id                    = $request->input('order_board.id');
        $order->protective_cover_id               = $request->input('protective_cover.id');
        $order->height_requirement_id             = $request->input('height_requirement.id');
        $order->delivery_note                     = $request->input('delivery_note');
        $order->note                              = $request->input('note');
        $order->requested_enclosure_delivery_date = $request->input('requested_enclosure_delivery_date');
        $order->ship_date                         = $request->input('ship_date');


        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_CREATED);
    }

    public function storeBulk(Request $request)
    {
        $data = $request->all();

        $data = OrderBulkValidator::validate($data);

        if ( ! OrderBulkValidator::isValid($data)) {

            return response([
                'data' => $data,
            ], Response::HTTP_BAD_REQUEST);
        }

        $arrOrder = [];
//        $arrOrderResource = [];
        for ($i = 0; $i < count($data); $i++) {

            $order              = [];
            $presell            = Presell::where('value', $data[$i]['presell'])->first();
            $order_board        = OrderBoard::where('value', $data[$i]['order_board'])->first();
            $protective_cover   = ProtectiveCover::where('value', $data[$i]['protective_cover'])->first();
            $height_requirement = HeightRequirement::where('value', $data[$i]['height_requirement'])->first();


            $order['nsn']                   = $data[$i]['nsn'];
            $order['presell_id']            = $presell->id;
            $order['order_board_id']        = $order_board->id;
            $order['protective_cover_id']   = $protective_cover->id;
            $order['height_requirement_id'] = $height_requirement->id;
            $order['delivery_note']         = $data[$i]['delivery_note'];
            $order['note']                  = $data[$i]['note'];
//            $order['requested_enclosure_delivery_date'] = DateTime::createFromFormat('m/d/Y',
//                $data[$i]['requested_enclosure_delivery_date'])->format('Y-m-d');

            $order['requested_enclosure_delivery_date'] = DateConvertor::toDate($data[$i]['requested_enclosure_delivery_date']);

            array_push($arrOrder, $order);
//            array_push($arrOrderResource, new OrderResource($order));
        }

//        Order::insert($arrOrder);

        return response([
            'data' => $arrOrder
        ], Response::HTTP_CREATED);
    }


    public static function validateBulk(Request $request)
    {
        $data = OrderBulkValidator::validate($request->all());

        if (OrderBulkValidator::isValid($data)) {

            return response([
                'data' => $data
            ], Response::HTTP_OK);

        } else {

            return response([
                'data' => $data
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_CREATED);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->nsn                               = $request->input('nsn');
        $order->presell_id                        = $request->input('presell.id');
        $order->order_board_id                    = $request->input('order_board.id');
        $order->protective_cover_id               = $request->input('protective_cover.id');
        $order->height_requirement_id             = $request->input('height_requirement.id');
        $order->delivery_note                     = $request->input('delivery_note');
        $order->note                              = $request->input('note');
        $order->requested_enclosure_delivery_date = $request->input('requested_enclosure_delivery_date');
        $order->ship_date                         = $request->input('ship_date');
//        return $order;
        $order->save();


        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_OK);
    }

    //VALIDATE


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

//        $order->delete();

        return response([
            'data' => null
        ], Response::HTTP_NO_CONTENT);
    }
}
