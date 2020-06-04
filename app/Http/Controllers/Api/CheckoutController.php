<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\CartProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MasterdataController;
use App\Http\Controllers\OngkirController;
use App\JiCash;
use App\JiCashHistory;
use App\Message;
use App\Notification;
use App\PaymentMethod;
use App\Product;
use App\ProductDetail;
use App\Store;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionDetailStatus;
use App\TransactionPayment;
use App\User;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{

    private $ongkir;

    public function __construct()
    {
        $this->ongkir = new OngkirController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $username = $request->username;
        $city_id = User::where('username', $username)->first()->city;

        $data['user'] = User::where('username', $username)->first();

        $data['store'] = Store::with(['expedition', 'product.cart' => function ($query) use ($username) {
            $query->where([['checkout_status', 1], ['username', $username]]);
        }, 'product.images' => function ($query) {
            $query->where('main_image', 'OPTYS');
        }])->whereHas('product.cart', function ($query) use ($username) {
            $query->where([['checkout_status', 1], ['username', $username]]);
        })->get();

        $i = 0;
        $j = 0;
        $data['total_price'] = 0;
        foreach ($data['store'] as $store) {
            $data['total_weight'][$i]['store_id'] = $store->id;
            $data['total_weight'][$i]['weight'] = 0;
            foreach ($store->product as $product) {
                foreach ($product->cart as $cart) {
                    if ($cart->username == $username) {
                        $data['total_price'] += $cart->price;
                        $data['total_weight'][$i]['weight'] += ($product->weight * $cart->quantity);
                    }
                }
            }

            foreach ($store->expedition as $expedition) {
                $expedition_service = $this->ongkir->getPrice($city_id, $data['total_weight'][$i]['weight'], $expedition['expedition_code']);
                $expedition['expedition_name'] = $expedition_service[0]['name'];
                $expedition['price'] = $expedition_service[0]['costs'];
            }

            $i++;
        }

        $data['payment_method'] = PaymentMethod::all();

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
        $seq = new MasterdataController();
        $trans_code = $seq->getTransactionSequence();
        $username = $request->username;
        $user_id = User::where('username', $username)->first()->id;
        $address = $request->address;
        $phone = $request->phone;
        $voucher_code = strtoupper($request->voucher) ?? null;
        $total_shipping_charges = $request->total_shipping_charges;
        $total_product_amount = $request->total_product_amount;
        $total_discount_amount = $request->total_discount_amount;
        $grand_total = $request->grand_total;
        $store_ids = $request->store_id;
        $expedition_stores = $request->expedition;
        $payment_method = $request->payment_method_id;
        $product_code = $request->product_code;
        $message = $request->message;

        $ji_cash_data = JiCash::where('username', $username)->first();

        if($ji_cash_data == null) {
            $ji_cash_data = JiCash::create([
                'username' => $username,
                'balance' => 0
            ]);
        }

        $pct = 0;
        if ($voucher_code != null) {
            $voucher = Voucher::where('code', $voucher_code)->first();
            $pct = $voucher->percentage ?? 0;
        }

        for ($i = 0; $i < count($product_code); $i++) {
            if ($message[$i] != null) {
                Cart::where(['product_code' => $product_code[$i], 'username' => $username])
                    ->update(['message' => $message[$i]]);
            }
        }

        $checkouts = Cart::with('product', 'product.store')
            ->where('checkout_status', 1)
            ->where('username', $username);

        $total_product = $checkouts->get()->count();
        $total_quantity = $checkouts->get()->sum('quantity');
        $total_weight = 0;
        foreach ($checkouts->get() as $checkout) {
            $total_weight += ($checkout->quantity * $checkout->product->weight);
        }

        if ($total_weight < 1) {
            $total_weight = 1;
        }

        //insert master data transaction from checkout
        $transaction = Transaction::create([
            'code' => $trans_code,
            'username' => $username,
            'address_1' => $address,
            'phone' => $phone,
            'total_product' => $total_product,
            'total_weight' => $total_weight,
            'total_quantity' => $total_quantity,
            'voucher_code' => ($voucher_code == "") ? null : $voucher_code,
            'shipping_charges' => $total_shipping_charges,
            'total_amount' => $total_product_amount,
            'discount_pct' => $pct,
            'discount_amount' => $total_discount_amount,
            'grand_total_amount' => $grand_total,
            'created_by' => $username,
            'updated_by' => $username,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //insert each transaction detail from checkout
        foreach ($checkouts->get() as $checkout) {
            //check product code berada di toko yang mana
            $product_code = $checkout->product_code;
            $product_store_code = $checkout->product->store->code;
            $expedition = "";
            for ($i = 0; $i < count($store_ids); $i++) {
                if ($store_ids[$i] == $product_store_code) {
                    $expedition = $expedition_stores[$i];
                }
            }

            TransactionDetail::insert([
                'transaction_code' => $trans_code,
                'product_code' => $product_code,
                'expedition' => $expedition, //edit
                'quantity' => $checkout->quantity,
                'weight' => ($checkout->quantity * $checkout->product->weight),
                'price' => $checkout->price,
                'message' => $checkout->message,
                'created_by' => $username,
                'updated_by' => $username,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => 'pembayaran'
            ]);
        }

        $detail_id = TransactionDetail::where('transaction_code', $trans_code)->first();

        //insert into payment transaction
        TransactionPayment::insert([
            'transaction_code' => $trans_code,
            'payment_method_id' => $payment_method,
            'deadline_proof' => Carbon::now()->add(1, 'day'),
            'created_by' => $username,
            'updated_by' => $username,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'updated_process' => 'pembayaran',
        ]);

        if ($payment_method == 1) {
            if ($ji_cash_data->balance < $grand_total) {
                return response('Saldo Ji-Cash Tidak Cukup', 422);
            }

            JiCashHistory::create([
                'ji_cash_id' => $ji_cash_data->id,
                'transaction_type' => 'Pembayaran Transaksi',
                'amount' => $grand_total,
                'created_by' => Carbon::now(),
                'updated_by' => Carbon::now()
            ]);

            JiCash::where('id', $ji_cash_data->id)
                ->update([
                    'balance' => $ji_cash_data->balance - $grand_total,
                    'updated_at' => Carbon::now()
                ]);

            if ($voucher_code != null) {
                $voucher = Voucher::where('code', $voucher_code)->first();
                if ($voucher->type == 'VCRCB') {
                    JiCashHistory::create([
                        'ji_cash_id' => $ji_cash_data->id,
                        'transaction_type' => 'Cashback',
                        'amount' => $total_discount_amount,
                        'created_by' => Carbon::now(),
                        'updated_by' => Carbon::now()
                    ]);

                    JiCash::where('id', $ji_cash_data->id)
                        ->update([
                            'balance' => $ji_cash_data->balance + $total_discount_amount,
                            'updated_at' => Carbon::now()
                        ]);
                }
            }

            $payment = TransactionPayment::where('transaction_code', $trans_code)->first();
            $payment->update([
                'verified_status' => 'OPTYS',
                'verified_date' => Carbon::now(),
                'updated_process' => 'dalam proses'
            ]);

            TransactionDetail::where('transaction_code', $trans_code)
                ->update([
                    'status' => 'dalam proses'
                ]);

            $admins = User::where('role', 'ROLAD')
                ->orWhere('role', 'ROLSA')
                ->get();

            foreach ($admins as $admin) {
                Notification::insert([
                    'id_users' => $admin->id,
                    'notification_message' => "Transaksi " . $trans_code . " sudah melakukan pembayaran.",
                    'info' => 'notification',
                    'notification_read' => 'OPTNO',
                    'created_at' => Carbon::now(),
                ]);
            }

            Notification::insert([
                'id_users' => $user_id,
                'notification_message' => "Transaksi " . $trans_code . " sedang diproses oleh penjual.",
                'info' => 'notification',
                'notification_read' => 'OPTNO',
                'created_at' => Carbon::now(),
            ]);
        }

        //updated stock product and delete cart
        foreach ($checkouts->get() as $checkout) {
            $products = Product::where('code', $checkout->product_code);
            $current_quantity = $products->select('stock')->get();
            $updated_quantity = $current_quantity[0]->stock - $checkout->quantity;

            $products->update([
                'stock' => $updated_quantity,
                'updated_at' => Carbon::now(),
            ]);

            if (isset($checkout->cart_product_status)) {
                $detail = ProductDetail::where('product_code', $checkout->product_code)
                    ->where('size', $checkout->cart_product_status->value);

                $current_quantity = $detail->select('stock')->get();
                $updated_quantity = $current_quantity[0]->stock - $checkout->quantity;

                $detail->update([
                    'stock' => $updated_quantity,
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        foreach ($checkouts->get() as $checkout) {
            if (isset($checkout->cart_product_status)) {
                TransactionDetailStatus::insert([
                    'id_detail' => $detail_id->id,
                    'status_code' => $checkout->cart_product_status->status_code,
                    'value' => $checkout->cart_product_status->value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                CartProductStatus::where('id_cart', $checkout->id)->delete();
            }
        }

        //chat ke penjual
        foreach ($checkouts->get() as $checkout) {
            $id_toko = $checkout->product->store->users->id;

            Message::insert([
                'to_user' => $id_toko,
                'from_user' => $user_id,
                'message' => 'Hai, saya telah membeli produk ' . $checkout->product->name . ', sebanyak ' . $checkout->quantity . ' Terima kasih',
                'created_at' => Carbon::now(),
            ]);

            Notification::insert([
                'id_users' => $id_toko,
                'notification_from' => $user_id,
                'notification_message' => $username . ' Mengirim anda pesan.',
                'info' => 'message',
                'notification_read' => 'OPTNO',
                'created_at' => Carbon::now(),
            ]);

            Notification::insert([
                'id_users' => $user_id,
                'notification_message' => "Pembelian " . $checkout->product->name . " sudah masuk tahap pembayaran.",
                'info' => 'notification',
                'notification_read' => 'OPTNO',
                'created_at' => Carbon::now(),
            ]);
        }

        $checkouts->delete();

        return response()->json($transaction, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
