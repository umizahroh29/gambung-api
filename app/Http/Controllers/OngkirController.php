<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class OngkirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllCities()
    {
        $client = new Client(['headers' => ['key' => config('api_key')]]);

        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/city');
        $data['cities'] = json_decode($response->getBody()->getContents(), true);
        $data = $data['cities']['rajaongkir']['results'];
        return $data;

    }

    public function getAllProvinces()
    {
        $client = new Client(['headers' => ['key' => config('api_key')]]);

        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province');
        $data['provinces'] = json_decode($response->getBody(), true);
        $data = $data['provinces']['rajaongkir']['results'];

        return $data;
    }

    public function getPrice($destination,$weight,$courier)
    {

      if ($weight < 1) {
        $weight = 1;
      }

      $client = new Client(['headers' => ['key' => config('api_key')]]);
      $response = $client->request('POST', 'https://api.rajaongkir.com/starter/cost',[
        'form_params' => [
          'origin' => "22",
          'destination' => $destination,
          'weight' => $weight,
          'courier' => $courier,
        ]
      ]);
      $data['prices'] = json_decode($response->getBody(), true);
      $data = $data['prices']['rajaongkir']['results'];

      return $data;
    }

}
