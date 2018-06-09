<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItImportResource;
use App\Http\Resources\OrderResource;
//use Illuminate\Auth\Access\Response;
use App\OrderBoard;
use App\ProtectiveCover;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Order;
use App\ItImport;
use App\Presell;
use App\HeightRequirement;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'data' => new OrderCollection(Order::with([
                'presell',
                'protective_cover',
                'order_board',
                'height_requirement'
            ])->get())
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requested_enclosure_delivery_date           = $request->input('requested_enclosure_delivery_date');
        $requested_enclosure_delivery_date_converted = date('Y-m-d', strtotime($requested_enclosure_delivery_date));

        $order = new Order();

        $order->nsn                               = $request->input('nsn');
        $order->presell_id                        = $request->input('presell.id');
        $order->order_board_id                    = $request->input('order_board.id');
        $order->protective_cover_id               = $request->input('protective_cover.id');
        $order->height_requirement_id             = $request->input('height_requirement.id');
        $order->delivery_note                     = $request->input('delivery_note');
        $order->note                              = $request->input('note');
        $order->requested_enclosure_delivery_date = $requested_enclosure_delivery_date_converted;
        $order->ship_date                         = $request->input('ship_date');
        $order->save();

        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_CREATED);
    }

    public function storeBulk(Request $request)
    {

        $data = $request->all();

//        return print_r($data);
        $data             = $data['data'];
        $arrOrder         = [];
        $arrOrderResource = [];
        for ($i = 0; $i < count($data); $i++) {
            $requested_enclosure_delivery_date           = $data[$i]['requested_enclosure_delivery_date'];
            $requested_enclosure_delivery_date_converted = date('Y-m-d', strtotime($requested_enclosure_delivery_date));

            $order              = [];
            $presell            = Presell::where('value', $data[$i]['presell'])->first();
            $order_board        = OrderBoard::where('value', $data[$i]['order_board'])->first();
            $protective_cover   = ProtectiveCover::where('value', $data[$i]['protective_cover'])->first();
            $height_requirement = HeightRequirement::where('value', $data[$i]['height_requirement'])->first();


            $order['nsn']                               = $data[$i]['nsn'];
            $order['presell_id']                        = $presell->id;
            $order['order_board_id']                    = $order_board->id;
            $order['protective_cover_id']               = $protective_cover->id;
            $order['height_requirement_id']             = $height_requirement->id;
            $order['delivery_note']                     = $data[$i]['delivery_note'];
            $order['note']                              = $data[$i]['note'];
            $order['requested_enclosure_delivery_date'] = $requested_enclosure_delivery_date_converted;

            array_push($arrOrder, $order);
            array_push($arrOrderResource, new OrderResource($order));
        }

        Order::insert($arrOrder);
//        DB::table('orders')->insert($arrOrder);
//        return $arrOrder;
        return response([
            'data' => $arrOrder
        ], Response::HTTP_CREATED);
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

        $requested_enclosure_delivery_date           = $request->input('requested_enclosure_delivery_date');
        $requested_enclosure_delivery_date_converted = date('Y-m-d', strtotime($requested_enclosure_delivery_date));

        $order->nsn                               = $request->input('nsn');
        $order->presell_id                        = $request->input('presell.id');
        $order->order_board_id                    = $request->input('order_board.id');
        $order->protective_cover_id               = $request->input('protective_cover.id');
        $order->height_requirement_id             = $request->input('height_requirement.id');
        $order->delivery_note                     = $request->input('delivery_note');
        $order->note                              = $request->input('note');
        $order->requested_enclosure_delivery_date = $requested_enclosure_delivery_date_converted;
        $order->ship_date                         = $request->input('ship_date');
        $order->save();

        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_CREATED);
    }

    //VALIDATE
    public function validateBulk(Request $request)
    {

        $data = $request->all();

        for ($i = 0; $i < count($data); $i++) {
            $requested_enclosure_delivery_date           = $data[$i]['requested_enclosure_delivery_date'];
            $requested_enclosure_delivery_date_converted = date('Y-m-d', strtotime($requested_enclosure_delivery_date));

            $data[$i]['errors'] = [];


            if (ItImport::where('nsn', $data[$i]['nsn'])->first() != null) {
                if (Order::where('nsn', $data[$i]['nsn'])->first()) {
                    array_push($data[$i]['errors'],
                        'An order has previously been submitted for this NSN. Please enter a new NSN or work with your Coates representative to update the order.');
                }
            } else {
                array_push($data[$i]['errors'],
                    'Please enter a valid NSN');
            }

            if ( ! Presell::where('value', $data[$i]['presell'])->first()) {
                array_push($data[$i]['errors'],
                    'Presells value is not valid.');
            }

            if ($data[$i]['presell'] === 0 && $data[$i]['order_board'] === 0) {
                array_push($data[$i]['errors'],
                    'Please select at least 1 Single Menu Board or 1 Double Menu Board.');
            }

            if ( ! ProtectiveCover::where('value', $data[$i]['protective_cover'])->first()) {
                array_push($data[$i]['errors'],
                    'Protective Cover value is not valid.');
            }

            if ( ! OrderBoard::where('value', $data[$i]['order_board'])->first()) {
                array_push($data[$i]['errors'],
                    'Order Board value is not valid.');
            }

            if ( ! HeightRequirement::where('value', $data[$i]['height_requirement'])->first()) {
                array_push($data[$i]['errors'],
                    'Height Requirements value is not valid.');
            }

            if (strlen($data[$i]['delivery_note']) > 255) {
                array_push($data[$i]['errors'],
                    'Delivery Notes field exceeds 255 characters.');
            }

            if (strlen($data[$i]['note']) > 255) {
                array_push($data[$i]['errors'],
                    'Notes field exceeds 255 characters.');
            }

        }

        return response([
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(
        $id
    ) {
        $order = Order::findOrFail($id);
        $order->delete();

        return response([
            'data' => null
        ], Response::HTTP_NO_CONTENT);
    }
}
