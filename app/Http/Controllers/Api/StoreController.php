<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MasterdataController;
use App\Store;
use Illuminate\Http\Request;
use Validator;

class StoreController extends Controller
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
        $data = Store::with('users', 'product.images', 'expedition')->get();
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

        $store = new Store();

        $code = $this->masterdata->getStoreSequence();

        $store->code = $code;
        $store->name = $request->name;
        $store->username = $request->username;
        $store->description = $request->description;
        $store->address_1 = $request->address;
        $store->phone_1 = $request->phone;
        $store->city = $request->city;

        $result = $store->save();

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
        $store = Store::find($id);
        if (is_null($store)) {
            return response()->json('Toko Tidak Ditemukan', 404);
        }

        $data = Store::with('users', 'product.images')->where('id', $id)->first();
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
        $store = Store::find($id);
        if (is_null($store)) {
            return response()->json('Toko Tidak Ditemukan', 404);
        }
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $validator->messages();
        }

        $store->name = $request->name;
        $store->username = $request->username;
        $store->description = $request->description;
        $store->address_1 = $request->address;
        $store->phone_1 = $request->phone;
        $store->city = $request->city;

        $result = $store->save();
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
        $store = Store::find($id);
        if (is_null($store)) {
            return response()->json('Toko Tidak Ditemukan', 404);
        }

        $result = $store->delete();
        if (!$result) {
            return response()->json('Gagal Hapus', 500);
        } else {
            return response()->json('Berhasil Hapus', 200);
        }
    }

    private function rules()
    {
        $cities_data = $this->masterdata->getCitiesForValidation();
        $cities = '';
        foreach ($cities_data as $city) {
            $cities .= $city['city_id'] . ',';
        }

        $users = $this->masterdata->getUsernameForValidation();

        return [
            'name' => 'required|max:255',
            'username' => 'required|max:255|in:' . implode(',', $users),
            'description' => 'nullable|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:13',
            'city' => 'required|in:' . $cities
        ];
    }
}
