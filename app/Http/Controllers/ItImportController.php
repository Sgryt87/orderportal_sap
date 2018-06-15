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
        $it_import = ItImport::orderBy('created_at', 'desc')->paginate(20);

//        return response()->json($it_import);
        return response([
            'data' => $it_import
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
    public function store(ItImportRequest $request)
    {
//import from CSV
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

        return response([
            'data' => new ItImportResource($it_import)
        ], Response::HTTP_OK);
    }

    public function showByNSN(Request $request)
    {
        $address = ItImport::where('nsn', $request->input('nsn'))->first();


        return response([
            'data' => $address
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


