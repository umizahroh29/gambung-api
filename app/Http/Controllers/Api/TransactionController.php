<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notification;
use App\Product;
use App\ProductDetail;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionHistory;
use App\TransactionPayment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->input('limit') == null) ? 5 : $request->input('limit');
        $data = TransactionDetail::with(['transaction.payment', 'transaction.history', 'product.store', 'product.images'])
            ->when($keyword = $request->get('username'), function ($query) use ($keyword) {
                $query->whereHas('transaction', function ($query) use ($keyword) {
                    $query->where('username', $keyword);
                });
            })->when($keyword = $request->get('store_code'), function ($query) use ($keyword) {
                $query->whereHas('product', function ($query) use ($keyword) {
                    $query->where('store_code', $keyword);
                })->whereHas('transaction.payment', function ($query) {
                    $query->where('verified_status', 'OPTYS');
                });
            })
            ->orderByDesc("created_at")
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionDetail $transaction)
    {
        return response($transaction->load('transaction.payment', 'transaction.history', 'product.store'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function delivery_confirmation(Request $request)
    {
        $rule = [
            'transaction_code' => 'required|string',
            'transaction_detail_id' => 'required|string',
            'shipping_no' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        TransactionDetail::where('id', $request->transaction_detail_id)->update([
            'shipping_status' => 'OPTSD',
            'shipping_no' => $request->shipping_no,
            'updated_by' => $request->user()->username,
            'updated_at' => Carbon::now(),
            'status' => 'pengiriman'
        ]);

        TransactionPayment::where('transaction_code', $request->transaction_code)->update([
            'updated_by' => $request->user()->username,
            'updated_at' => Carbon::now(),
            'updated_process' => 'pengiriman'
        ]);

        $transaction = Transaction::with('users')->where('code', $request->transaction_code)->first();

        Notification::insert([
            'id_users' => $transaction->users->id,
            'notification_message' => "Transaksi " . $request->transaction_code . " sudah dikirim, jangan lupa menekan tombol diterima jika sudah sampai.",
            'info' => 'notification',
            'notification_read' => 'OPTNO',
            'created_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Memasukkan No Resi'
        ], 200);
    }

    public function upload_proof(Request $request)
    {
        $username = $request->username;
        $file = $request->file('proof_image');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move(public_path('assets/img/proof/'), $nama_file);

        TransactionPayment::where('transaction_code', $request->transaction_code)
            ->update([
                'deadline_proof' => Carbon::now()->add(1, 'day'),
                'status_upload' => 'OPTYS',
                'proof_image' => $nama_file,
                'proof_date' => Carbon::now(),
                'updated_by' => Carbon::now(),
                'updated_process' => 'verifikasi',
            ]);

        TransactionDetail::where('transaction_code', $request->transaction_code)
            ->update([
                'status' => 'verifikasi'
            ]);

        Notification::insert([
            'id_users' => User::where('username', $username)->first()->id,
            'notification_message' => "Transaksi " . $request->code . " sudah masuk tahap verifikasi.",
            'info' => 'notification',
            'notification_read' => 'OPTNO',
            'created_at' => Carbon::now(),
        ]);

        $admins = User::where('role', 'ROLAD')
            ->orWhere('role', 'ROLSA')
            ->get();

        foreach ($admins as $admin) {
            Notification::insert([
                'id_users' => $admin->id,
                'notification_message' => "Transaksi " . $request->code . " sudah melakukan pembayaran.",
                'info' => 'notification',
                'notification_read' => 'OPTNO',
                'created_at' => Carbon::now(),
            ]);
        }

        return response('Berhasil Upload', 200);
    }

    public function cancel(Request $request)
    {
        $transaction_code = $request->transaction_code;
        $product_code = $request->product_code;
        $username = $request->username;

        $details = TransactionDetail::where('transaction_code', $transaction_code)
            ->where('product_code', $product_code);

        $products = $details->get();
        if ($products[0]->product->product_detail == null) {
            Product::where('code', $product_code)
                ->increment('stock', $products[0]->quantity);
        } else {
            $temp = Product::where('code', $product_code);
            $get_product = $temp->get();
            $status = $get_product[0];
            foreach ($status->product_detail as $detail) {
                if ($detail->size == $products[0]->status->value) {
                    ProductDetail::where('product_code', $product_code)
                        ->where('size', $detail->size)
                        ->increment('stock', $products[0]->quantity);
                }
            }
            $temp->increment('stock', $products[0]->quantity);
        }

        $details->update([
            'shipping_status' => 'OPTCC',
            'updated_at' => Carbon::now(),
            'updated_by' => $username,
        ]);

        TransactionHistory::insert([
            'transaction_code' => $transaction_code,
            'product_code' => $product_code,
            'status' => 'canceled',
            'created_by' => $username,
            'updated_by' => $username,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $check = TransactionDetail::where('transaction_code', $transaction_code)
            ->where('shipping_status', '!=', 'OPTCC');

        $products = $check->get();

        if ($check->count() > 0) {
            $totalProduct = $products->count();
            $totalQuantity = $products->sum('quantity');
            $totalAmount = $products->sum('price');
            $totalWeight = $products->sum('weight');

            //get new voucher
            $voucher_code = $products[0]->transaction->voucher_code;
            if ($voucher_code != null) {
                $max_price = $products[0]->transaction->voucher->max_price;
                $discount_pct = $products[0]->transaction->discount_pct;
                $diskon = ($discount_pct * $totalAmount / 100);
                if ($diskon > $max_price) {
                    $voucher_code = null;
                    $diskon = 0;
                    $discount_pct = 0;
                }

                //get new expedition
                $expedition = $products[0]->expedition;
                $exp[] = explode(' ', $expedition);
                $ket = array_pop($exp[0]);
                array_pop($exp[0]);
                $exp = substr(array_pop($exp[0]), 1, 3);
                $city_id = $products[0]->transaction->users->city;
                $cost = $this->ongkir->getPrice($city_id, $totalWeight, strtolower($exp));
                $totalExpedisi = 0;
                foreach ($cost[0]['costs'] as $detail) {
                    if ($detail['service'] == $ket) {
                        $totalExpedisi = $detail['cost'][0]['value'];
                    }
                }

                $grand_total = $totalAmount - $diskon + $totalExpedisi;

                Transaction::where('code', $transaction_code)
                    ->update([
                        'total_product' => $totalProduct,
                        'total_weight' => $totalWeight,
                        'total_quantity' => $totalQuantity,
                        'voucher_code' => $voucher_code,
                        'shipping_charges' => $totalExpedisi,
                        'total_amount' => $totalAmount,
                        'discount_pct' => $discount_pct,
                        'discount_amount' => $diskon,
                        'grand_total_amount' => $grand_total,
                        'updated_by' => $username,
                        'updated_at' => Carbon::now(),
                    ]);
            }
        } else {
            Transaction::where('code', $transaction_code)
                ->update([
                    'total_product' => 0,
                    'total_weight' => 0,
                    'total_quantity' => 0,
                    'voucher_code' => null,
                    'shipping_charges' => 0,
                    'total_amount' => 0,
                    'discount_pct' => 0,
                    'discount_amount' => 0,
                    'grand_total_amount' => 0,
                    'updated_by' => $username,
                    'updated_at' => Carbon::now(),
                ]);
        }

        return response('Transaksi Berhasil Dibatalkan', 200);
    }

    public function accept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_code' => 'required|string',
            'product_code' => 'required|string',
            'username' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $transaction_code = $request->transaction_code;
        $product_code = $request->product_code;
        $username = $request->username;

        TransactionDetail::where('transaction_code', $transaction_code)
            ->where('product_code', $product_code)
            ->update([
                'shipping_status' => 'OPTRC',
                'updated_at' => Carbon::now(),
                'updated_by' => $username,
            ]);

        TransactionHistory::insert([
            'transaction_code' => $transaction_code,
            'product_code' => $product_code,
            'status' => 'accepted',
            'created_by' => Carbon::now(),
            'updated_by' => Carbon::now(),
        ]);

        return response('Transaksi Berhasil Diproses', 200);
    }
}
