<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MasterdataController;
use App\JiCash;
use Validator;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class UsersController extends Controller
{

    use RegistersUsers;

    private $ongkir;
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
        $data = User::with('store', 'jicash')->get();
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

        // $user = new User();
        //
        // $user->username = $request->username;
        // $user->email = $request->email;
        // $user->name = $request->name;
        // $user->password = bcrypt($request->password);
        // $user->phone = $request->phone;
        // $user->birthday = $request->birthday;
        // $user->address_1 = $request->address;
        // $user->city = $request->city;
        // $user->role = $request->role;
        //
        // $result = $user->save();

        $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'username' => $request->username,
          'phone' => $request->phone,
          'address_1' => $request->address,
          'birthday' => $request->birthday,
          'password' => bcrypt($request->password),
          'city' => $request->city,
          'role' => $request->role,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]);

        if (!$user) {
            return response('Gagal Simpan', 500);
        } else {
            JiCash::create([
                'username' => $request->username,
                'balance' => 0
            ]);
            event(new Registered($user));
            return response('Berhasil Simpan', 201);
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
        $user = User::find($id);
        if (is_null($user)) {
            return response('User Tidak Ditemukan', 404);
        }

        $data = User::with('store', 'jicash')->where('id', $id)->get();
        return response($data, 200);
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
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('User Tidak Ditemukan', 404);
        }

        $cities_data = $this->masterdata->getCitiesForValidation();
        $cities = '';
        foreach ($cities_data as $city) {
            $cities .= $city['city_id'] . ',';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required|max:13',
            'birthday' => 'date|nullable',
            'address' => 'required|max:255',
            'city' => 'required|in:' . $cities
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->birthday = $request->birthday;
        $user->address_1 = $request->address;
        $user->city = $request->city;

        $result = $user->save();
        if (!$result) {
            return response('Gagal Simpan', 500);
        } else {
            return response('Berhasil Simpan', 201);
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
        $user = User::find($id);
        if (is_null($user)) {
            return response('User Tidak Ditemukan', 404);
        }

        $result = $user->delete();
        if (!$result) {
            return response('Gagal Hapus', 500);
        } else {
            return response('Berhasil Hapus', 201);
        }
    }

    private function rules()
    {
        $cities_data = $this->masterdata->getCitiesForValidation();
        $cities = '';
        foreach ($cities_data as $city) {
            $cities .= $city['city_id'] . ',';
        }

        return [
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'name' => 'required|max:255',
            'password' => 'required|confirmed|max:255',
            'password_confirmation' => 'required|max:255',
            'phone' => 'required|max:13',
            'birthday' => 'date|nullable',
            'address' => 'required|max:255',
            'role' => 'required|in:ROLSA,ROLAD,ROLPJ,ROLPB',
            'city' => 'required|in:' . $cities
        ];
    }
}
