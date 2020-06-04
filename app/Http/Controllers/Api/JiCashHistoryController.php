<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\JiCash;
use App\JiCashHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JiCashHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = JiCashHistory::when($keyword = $request->username, function ($query) use ($keyword) {
            $query->whereHas('jicash', function ($q) use ($keyword) {
               $q->where('username', $keyword);
            });
        })->get();

        return response()->json($data, 200);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ji_cash_data = JiCash::where('username', $request->username)->first();

        if($ji_cash_data == null) {
            $ji_cash_data = JiCash::create([
                'username' => $request->username,
                'balance' => 0
            ]);
        }

        $image = $request->file('topup_proof');
        $image_name = rand() . $image->getClientOriginalName();
        $image->move(public_path('assets/img/proof/'), $image_name);

        //update proof
        $history_id = $request->jicash_id;
        if ($history_id != null) {
          JiCashHistory::find($history_id)->update([
            'topup_proof_image' => '/' . $image_name,
            'updated_by' => Carbon::now()
          ]);

          return response()->json('Berhasil Update Proof', 200);
        }

        DB::beginTransaction();

        try {
            JiCashHistory::create([
                'ji_cash_id' => $ji_cash_data->id,
                'transaction_type' => 'Topup',
                'amount' => $request->amount,
                'topup_proof_image' => '/' . $image_name,
                'created_by' => Carbon::now(),
                'updated_by' => Carbon::now()
            ]);

            DB::commit();

            return response()->json('Berhasil Topup', 200);
        } catch (\Exception $e) {
            DB::rollback();

            dd($e);

            return response()->json('Gagal Topup', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\JiCashHistory $jiCashHistory
     * @return \Illuminate\Http\Response
     */
    public function show(JiCashHistory $jiCashHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\JiCashHistory $jiCashHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(JiCashHistory $jiCashHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\JiCashHistory $jiCashHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JiCashHistory $jiCashHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\JiCashHistory $jiCashHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(JiCashHistory $jiCashHistory)
    {
        //
    }
}
