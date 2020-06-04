<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->input('limit') == null) ? 10 : $request->input('limit');

        $data = Wishlist::with('products.images', 'users')
            ->when($keyword = $request->get('user_id'), function ($query) use ($keyword) {
                $query->where('id_users', $keyword);
            })
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
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $check = Wishlist::where([['id_users', $request->user_id], ['product_code', $request->product_code]])->first();
        if ($check != null) {
            return response('Produk Ini Sudah Ada dalam Wishlist User', 422);
        }

        $wishlist = new Wishlist();
        $wishlist->id_users = $request->user_id;
        $wishlist->product_code = $request->product_code;

        $result = $wishlist->save();
        if (!$result) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Wishlist $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        return response($wishlist->load('products', 'users'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Wishlist $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Wishlist $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        $result = $wishlist->delete();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    public function rules()
    {
        return [
            'user_id' => 'required|string|exists:users,id',
            'product_code' => 'required|string|exists:tb_product,code',
        ];
    }
}
