<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->input('limit') == null) ? 5 : $request->input('limit');

        $data = Cart::with(['product.store', 'product.images' => function ($q) {
            $q->where('main_image', 'OPTYS');
        }])->when($keyword = $request->get('username'), function ($query) use ($keyword) {
            $query->where('username', $keyword);
        })->paginate($limit);
        return response($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return $validator->messages();
        }

        $product_code = $request->product_code;
        $quantity = $request->quantity;
        $price = Product::where('code', $request->product_code)->first()->price;
        $username = $request->username;
        $value = $request->size;

        $data = Cart::with('cart_product_status')
            ->where('product_code', $product_code)
            ->where('username', $username)
            ->get();

        $status = Product::with('product_detail')
            ->where('code', $product_code)
            ->get();

        $cart = Cart::where(['product_code' => $product_code, 'username' => $username])->first();
        if ($cart != null) {
            $quantity += $cart->quantity;
        }

        foreach ($data as $dt) {
            if ($dt->cart_product_status != null) {
                if ($dt->cart_product_status->value == $value) {
                    DB::table('tb_cart')
                        ->updateOrInsert(
                            [
                                'product_code' => $product_code,
                                'username' => $username,
                            ],
                            [
                                'quantity' => $quantity,
                                'price' => $price * $quantity,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                    $id = DB::table('tb_cart')
                        ->where('product_code', $product_code)
                        ->where('username', $username)
                        ->orderBy('created_at', 'DESC')
                        ->get();

                    DB::table('cart_product_status')->updateOrInsert(
                        [
                            'id_cart' => $id[0]->id,
                            'status_code' => "STS01",
                        ],
                        [
                            'value' => $value,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                    return response()->json('Berhasil Simpan', 200);
                }
            } else {
                DB::table('tb_cart')->updateOrInsert(
                    [
                        'product_code' => $product_code,
                        'username' => $username,
                    ],
                    [
                        'quantity' => $quantity,
                        'price' => $price * $quantity,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                return response()->json('Berhasil Simpan', 200);
            }
        }

        DB::table('tb_cart')->insert([
            'product_code' => $product_code,
            'username' => $username,
            'quantity' => $quantity,
            'price' => $price * $quantity,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($value != null) {

            $id = DB::table('tb_cart')
                ->where('product_code', $product_code)
                ->where('username', $username)
                ->orderBy('created_at', 'DESC')
                ->get();

            DB::table('cart_product_status')->insert([
                'id_cart' => $id[0]->id,
                'status_code' => "STS01",
                'value' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }

        return response()->json('Berhasil Simpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $carts = $request->cart_id;
        $quantities = $request->quantity;
        $messages = $request->message;
        $username = $request->username;

        Cart::where('username', $username)
            ->update([
                'checkout_status' => 0,
            ]);

        $i = 0;
        foreach ($carts as $cart) {
            $price = Cart::with('product')
                ->where('id', $cart)->first();

            $total = ($price->product->price * $quantities[$i]);

            DB::table('tb_cart')
                ->where('id', $cart)
                ->update([
                    'quantity' => $quantities[$i],
                    'price' => $total,
                    'message' => $messages[$i],
                    'checkout_status' => 1,
                ]);

            $i++;
        }

        return response()->json('Berhasil Simpan', 200);
    }

    public function checkout(Request $request)
    {
        $carts = $request->cart_id;
        $quantities = $request->quantity;
        $messages = $request->message;
        $username = $request->username;

        foreach ($carts as $cart_id) {
          $quantity = Cart::find($cart_id)->product->stock;
          if ($quantity == 0) {
            Cart::find($cart_id)->delete();
            return response()->json('Stock Habis', 406);
          }
        }

        DB::beginTransaction();
        Cart::where('username', $username)
            ->update([
                'checkout_status' => 0,
            ]);

        $i = 0;
        foreach ($carts as $cart) {
            $price = Cart::with('product')
                ->where('id', $cart)->first();

            $total = ($price->product->price * $quantities[$i]);

            DB::table('tb_cart')
                ->where('id', $cart)
                ->update([
                    'quantity' => $quantities[$i],
                    'price' => $total,
                    'message' => $messages[$i],
                    'checkout_status' => 1,
                ]);

            $i++;
        }
        DB::commit();

        return response()->json('Berhasil Simpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $result = $cart->delete();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    private function rules()
    {
        return [
            'product_code' => 'required|exists:tb_product,code',
            'quantity' => 'required',
            'username' => 'required',
            'size' => 'nullable'
        ];
    }
}
