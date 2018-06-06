<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItImportRequest;
use App\Http\Resources\ItImportCollection;
use App\Http\Resources\ItImportResource;
use Illuminate\Http\Request;
use App\Order;
use App\ItImport;
use Symfony\Component\HttpFoundation\Response;

class ItImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $it_import = ItImport::paginate(10);

        return response()->json($it_import);
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
    public function store(ItImportRequest $request)
    {
        $it_import = new ItImport();

        $this->nsn                               = $request->input('nsn');
        $this->store_address                     = $request->input('store_address');
        $this->store_city                        = $request->input('store_city');
        $this->store_zip                         = $request->input('store_zip');
        $this->restaurant_drive_thru_type        = $request->input('restaurant_drive_thru_type');
        $this->coates_hardware_board_type        = $request->input('coates_hardware_board_type');
        $this->coates_hardware_screen_protection = $request->input('coates_hardware_screen_protection');
        $this->coates_hardware_number_of_screens = $request->input('coates_hardware_number_of_screens');
        $this->coates_hardware_configuration     = $request->input('coates_hardware_configuration');
        $this->coates_order_received_date        = $request->input('coates_order_received_date');
        $this->coates_order_quote_submitted      = $request->input('coates_order_quote_submitted');
        $this->coates_order_po_approved          = $request->input('coates_order_po_approved');
        $this->coates_order_ship_date            = $request->input('coates_order_ship_date');

        return new ItImportResource($it_import);
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
        $it_import = ItImport::findOrFail($id);

        return new ItImportResource($it_import);
    }

    public function showByNSN(Request $request)
    {
//        $data = $request->all();
//        $arr  = [];
//        for ($i = 0; $i < count($data); $i++) {
//            $arr = $data[$i];
//        }
//
//        return $arr;


        $address = ItImport::where('nsn', 80)->first();

        return response([
            'data' => new ItImportResource($address)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


