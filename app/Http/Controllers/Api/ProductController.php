<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImages;
use App\TransactionDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\MasterdataController;

class ProductController extends Controller
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
    public function index(Request $request)
    {
        $limit = ($request->input('limit') == null) ? 10 : $request->input('limit');

        if ($request->get('code')) {
            $data = Product::with('images', 'cart', 'store', 'wishlists', 'reviews', 'product_detail')
                ->where('code', $request->get('code'))
                ->withCount('wishlists', 'transaction_detail')
                ->first();

            return response($data, 200);
        }

        $data = Product::with('images', 'cart', 'store', 'wishlists', 'reviews', 'product_detail')
            ->when($keyword = $request->get('store_code'), function ($query) use ($keyword) {
                $query->where('store_code', $keyword);
            })
            ->when($keyword = $request->get('product_name'), function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
            })
            ->withCount('wishlists', 'transaction_detail')
            ->get();
//            ->paginate($limit);

        $response['data'] = $data;
        return response($response, 200);
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

        $product = new Product();
        $product_code = $this->masterdata->getProductSequence();

        $product->code = $product_code;
        $product->store_code = $request->store_code;
        $product->name = $request->name;
        $product->main_category = $request->main_category;
        $product->sub_category = $request->sub_category;
        $product->description = $request->description;
        $product->weight = $request->weight;
        $product->stock = $request->stock;
        $product->color = $request->color;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->length = $request->length;
        $product->price = $request->price;

        $productImage = new ProductImages();

        $image = $request->file('image');
        $image_name = rand() . $image->getClientOriginalName();
        $image->move(public_path('assets/img/products/'), $image_name);

        $productImage->product_code = $product_code;
        $productImage->image_name = "/" . $image_name;
        $productImage->main_image = 'OPTYS';

        $result = $product->save();
        $result_image = $productImage->save();
        if (!$result || !$result_image) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $data = $product->load('images', 'cart', 'store', 'wishlists', 'reviews', 'product_detail')->loadCount('wishlists', 'transaction_detail');

        return response($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), $this->rules($request->method()));

        if ($validator->fails()) {
            return $validator->messages();
        }

        $product->name = $request->name;
        $product->main_category = $request->main_category;
        $product->sub_category = $request->sub_category;
        $product->description = $request->description;
        $product->weight = $request->weight;
        $product->stock = $request->stock;
        $product->color = $request->color;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->length = $request->length;
        $product->price = $request->price;

        $result = $product->save();
        if (!$result) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $count = TransactionDetail::where('product_code', $product->code)
        ->where('shipping_status','!=', 'OPTRC')
        ->where('shipping_status','!=','OPTFL')
        ->count();
        if ($count > 0) {
          $product->update(['stock' => 0]);
          return response('Stock menjadi 0', 202);
        }

        DB::beginTransaction();
        $product->cart()->delete();
        $product->images()->delete();
        $result = $product->delete();
        DB::commit();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    public function search(Request $request)
    {
        $value = $request->value;

        $data = Product::with('images', 'store', 'cart', 'wishlists', 'reviews')
            ->when(is_array($value), function ($query) use ($value) {
                $query->whereIn('main_category', $value);
            })
            ->when(!is_array($value), function ($query) use ($value) {
                $query->whereHas('store', function (Builder $query) use ($value) {
                    $query->where('name', 'LIKE', "%$value%");
                })->orWhere('name', 'LIKE', "%$value%");
            })
            ->get();

        return response($data, 200);
    }

    private function rules($method)
    {
        if ($method == 'POST') {
            return [
                'store_code' => 'required|string|exists:tb_store,code',
                'name' => 'required|string',
                'main_category' => 'required|string|exists:tb_category,code',
                'sub_category' => 'nullable|string|exists:tb_category,code',
                'description' => 'nullable|string',
                'weight' => 'required|numeric',
                'stock' => 'required|integer',
                'color' => 'sometimes|string',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'price' => 'required|numeric',
                'image' => 'required|image',
            ];
        } else {
            return [
                'name' => 'required|string',
                'main_category' => 'required|string|exists:tb_category,code',
                'sub_category' => 'nullable|string|exists:tb_category,code',
                'description' => 'nullable|string',
                'weight' => 'required|numeric',
                'stock' => 'required|integer',
                'color' => 'sometimes|string',
                'width' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'price' => 'required|numeric'
            ];
        }
    }
}
