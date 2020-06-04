<?php

namespace App\Http\Controllers\Api;

use App\Expedition;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MasterdataController;
use Illuminate\Http\Request;
use Validator;

class ExpeditionController extends Controller
{
    private $masterdata;

    public function __construct()
    {
        $this->masterdata = new MasterdataController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Expedition::all();
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $validator->messages();
        }

        $expedition = new Expedition();

        $code = $this->masterdata->getExpeditionSequence();
        $expedition->code = $code;
        $expedition->api_code = $request->api_code;
        $expedition->name = $request->name;

        $result = $expedition->save();
        if (!$result) {
            return response()->json('Gagal Simpan', 500);
        } else {
            return response()->json('Berhasil Simpan', 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expedition = Expedition::find($id);
        if (is_null($expedition)) {
            return response()->json('Ekspedisi Tidak Ditemukan', 404);
        }

        $data = Expedition::where('id', $id)->get();
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expedition = Expedition::find($id);
        if (is_null($expedition)) {
            return response()->json('Ekspedisi Tidak Ditemukan', 404);
        }

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $validator->messages();
        }

        $expedition->api_code = $request->api_code;
        $expedition->name = $request->name;

        $result = $expedition->save();
        if (!$result) {
            return response()->json('Gagal Simpan', 500);
        } else {
            return response()->json('Berhasil Simpan', 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expedition = Expedition::find($id);
        if (is_null($expedition)) {
            return response()->json('Ekspedisi Tidak Ditemukan', 404);
        }

        $result = $expedition->delete();
        if (!$result) {
            return response()->json('Gagal Hapus', 500);
        } else {
            return response()->json('Berhasil Hapus', 200);
        }
    }

    private function rules()
    {
        return [
            'api_code' => 'required|max:255',
            'name' => 'required|max:255'
        ];
    }
}
