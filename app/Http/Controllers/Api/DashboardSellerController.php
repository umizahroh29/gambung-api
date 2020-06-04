<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Store;
use App\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSellerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        DB::enableQueryLog();
        $username = \auth('api')->user()->username;
        $user_store = Store::select('code')->where('username', $username)->first();

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        $today = Carbon::now();
        $this_period = Carbon::now()->translatedFormat('F Y');

        $data['period'] = $this_period;
        $data['total_transaction'] = TransactionDetail::whereHas('product.store', function ($query) use ($user_store, $today) {
            $query->where('code', $user_store['code']);
        })->where('shipping_status', 'OPTRC')->whereRaw('MONTH(created_at) = ' . $today->month . ' AND YEAR(created_at) = ' . $today->year)->sum('price');

        $data['success_transation'] = TransactionDetail::whereHas('product.store', function ($query) use ($user_store, $today) {
            $query->where('code', $user_store['code']);
        })->where('shipping_status', 'OPTRC')->whereRaw('MONTH(created_at) = ' . $today->month . ' AND YEAR(created_at) = ' . $today->year)->count();

        $data['pending_transation'] = TransactionDetail::whereHas('product.store', function ($query) use ($user_store, $today) {
            $query->where('code', $user_store['code']);
        })->whereHas('transaction.payment', function ($query) {
            $query->where('verified_status', 'OPTYS');
        })->where('shipping_status', 'OPTNO')->whereRaw('MONTH(created_at) = ' . $today->month . ' AND YEAR(created_at) = ' . $today->year)->count();

        $data['fail_transation'] = TransactionDetail::whereHas('product.store', function ($query) use ($user_store, $today) {
            $query->where('code', $user_store['code']);
        })->where('shipping_status', 'OPTFL')->whereRaw('MONTH(created_at) = ' . $today->month . ' AND YEAR(created_at) = ' . $today->year)->count();

        $data['last_transaction'] = TransactionDetail::with('transaction.users')
            ->whereHas('product.store', function ($query) use ($user_store, $today) {
                $query->where('code', $user_store['code']);
            })->orderBy('created_at', 'desc')->paginate(5);

        return response($data, 200);
    }
}
