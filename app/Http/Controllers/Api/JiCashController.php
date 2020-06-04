<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\JiCash;
use Illuminate\Http\Request;

class JiCashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = JiCash::with(['history' => function ($query) {
        //     $query->whereRaw('is_topup_approved = (case when transaction_type = \'Topup\' then \'OPTYS\' else \'OPTNO\' end)');
        // }])->when($keyword = $request->get('username'), function ($query) use ($keyword) {
        //     $query->where('username', $keyword);
        // })->get();

        $data = JiCash::with(['history'])->when($keyword = $request->get('username'), function ($query) use ($keyword) {
            $query->where('username', $keyword);
        })->get();

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JiCash  $jiCash
     * @return \Illuminate\Http\Response
     */
    public function show(JiCash $jiCash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JiCash  $jiCash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JiCash $jiCash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JiCash  $jiCash
     * @return \Illuminate\Http\Response
     */
    public function destroy(JiCash $jiCash)
    {
        //
    }
}
