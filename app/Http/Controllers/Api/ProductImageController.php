<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = ProductImages::with('product')
            ->when($keyword = $request->get('product_code'), function ($query) use ($keyword) {
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
        $validator = Validator::make($request->all(), $this->rules($request->method()));

        if ($validator->fails()) {
            return $validator->messages();
        }

        $productImage = new ProductImages();

        $image = $request->file('image');
        $image_name = rand() . $image->getClientOriginalName();
        $image->move(public_path('assets/img/products/'), $image_name);

        $productImage->product_code = $request->product_code;
        $productImage->image_name = "/" . $image_name;
        $productImage->main_image = $request->main_image;

        $result = $productImage->save();
        if (!$result) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ProductImages $productImages
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ProductImages::where('id', $id)->first();
        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ProductImages $productImages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productImages = ProductImages::find($id);

        $validator = Validator::make($request->all(), $this->rules($request->method()));

        if ($validator->fails()) {
            return $validator->messages();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = rand() . $image->getClientOriginalName();
            $image->move(public_path('assets/img/products/'), $image_name);

            $productImages->image_name = "/" . $image_name;
        }

        $productImages->main_image = $request->main_image;

        $result = $productImages->save();
        if (!$result) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ProductImages $productImages
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productImages = ProductImages::find($id);
        $result = $productImages->delete();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    private function rules($method)
    {
        if ($method == 'POST') {
            return [
                'product_code' => 'required|string|exists:tb_product,code',
                'image' => 'required|image',
                'main_image' => 'required|string|in:OPTYS,OPTNO',
            ];
        } else {
            return [
                'image' => 'nullable|image',
                'main_image' => 'nullable|string|in:OPTYS,OPTNO',
            ];
        }
    }
}
