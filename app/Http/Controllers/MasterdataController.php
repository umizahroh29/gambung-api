<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterdataController extends Controller
{
    private $ongkir;

    public function __construct()
    {
        $this->ongkir = new OngkirController();
    }

    public function getStoreSequence()
    {
        $seq = DB::select("SELECT getStoreSequence() as seq");
        return $seq[0]->seq;
    }

    public function getExpeditionSequence()
    {
        $seq = DB::select("SELECT getExpeditionSequence() as seq");
        return $seq[0]->seq;
    }

    public function getProductSequence()
    {
        $seq = DB::select("SELECT getProductSequence() as seq");
        return $seq[0]->seq;
    }

    public function getTransactionSequence()
    {
        $seq = DB::select("SELECT getTransactionSequence() as seq");
        return $seq[0]->seq;
    }

    public function getMainCategorySequence()
    {
        $seq = DB::select("SELECT getCategorySequence() as seq");
        return $seq[0]->seq;
    }

    public function getSubCategorySequence()
    {
        $seq = DB::select("SELECT getSubCategorySequence() as seq");
        return $seq[0]->seq;
    }

    public function getStatusSequence()
    {
        $seq = DB::select("SELECT getStatusSequence() as seq");
        return $seq[0]->seq;
    }

    public function getCitiesForValidation()
    {
        $cities = $this->ongkir->getAllCities();

        $i = 0;
        $data = [];
        foreach ($cities as $city) {
            $data[$i]['city_id'] = $city['city_id'];
            $data[$i]['city_name'] = $city['city_name'];
            $data[$i]['province_id'] = $city['province_id'];
            $data[$i]['province_name'] = $city['province'];
            $i++;
        }

        return $data;
    }

    public function getUsernameForValidation()
    {
        $users = User::select('username')->get();

        $i = 0;
        $username = [];
        foreach ($users as $user) {
            $username[$i] = $user->username;
            $i++;
        }

        return $username;
    }

    public function getMainCategoryForValidation()
    {
        $categories = Category::select('code')->where('level', 'Main Category')->get();

        $i = 0;
        $mainCategory = [];
        foreach ($categories as $category) {
            $mainCategory[$i] = $category->code;
            $i++;
        }

        return $mainCategory;
    }
}
