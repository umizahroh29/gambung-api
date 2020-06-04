<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = ProductDetail::when($keyword = $request->get('product_code'), function ($query) use ($keyword) {
            $query->where('product_code', $keyword);
        })->get();

        return response($data, 200);
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

        $i = 0;
        foreach ($request->size as $size) {
            $productDetail = new ProductDetail();
            $productDetail->product_code = $request->product_code;
            $productDetail->size = $size;
            $productDetail->stock = $request->stock[$i];

            $productDetail->save();

            $i++;
        }

        return response('Berhasil Simpan', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ProductDetail $productDetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ProductDetail::where('id', $id)->first();
        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ProductDetail $productDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDetail $productDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ProductDetail $productDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productDetail = ProductDetail::find($id);
        $result = $productDetail->delete();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    private function rules()
    {
        return [
            'product_code' => 'required|string|exists:tb_product,code',
            'size' => 'array',
            'size.*' => 'required|string|distinct',
            'stock' => 'array',
            'stock.*' => 'required|numeric',
        ];
    }
}
