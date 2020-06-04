<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Alert;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
  * Where to redirect users after registration.
  *
  * @var string
  */
  protected $redirectTo = '/home';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest');
  }

  protected function showRegistrationForm()
  {
    $client = new Client(['headers' => ['key' => config('api_key')]]);

    $response = $client->request('GET', 'https://api.rajaongkir.com/starter/city');
    $data['cities'] = json_decode($response->getBody()->getContents(), true);

    $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province');
    $data['provinces'] = json_decode($response->getBody(),true);

    return view('auth.register', $data);

  }

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {

    return Validator::make($data, [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
      'username' => ['required', 'string', 'unique:users'],
      'telfon' => ['required', 'numeric', 'phone'],
      'alamat' => ['required', 'string'],
    ]);

  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return \App\User
  */
  protected function create(array $data)
  {

    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'username' => $data['username'],
      'phone' => $data['telfon'],
      'address_1' => $data['alamat'],
      'password' => bcrypt($data['password']),
      'city' => $data['kota'],
      'role' => 'ROLPB',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ]);
  }

}
