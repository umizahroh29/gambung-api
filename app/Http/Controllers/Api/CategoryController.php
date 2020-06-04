<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MasterdataController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Category;
use Validator;

class CategoryController extends Controller
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
//        $limit = ($request->input('limit') == null) ? 5 : $request->input('limit');
        $data = Category::all();
        $response['data'] = $data;
        return $response;
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

        $category = new Category();

        $code = ($request->level == 'Main Category') ? $this->masterdata->getMainCategorySequence() : $this->masterdata->getSubCategorySequence();

        $category->code = $code;
        $category->name = $request->name;
        $category->level = $request->level;
        $category->main_category = $request->main_category;

        $result = $category->save();
        if (!$result) {
            return response()->json('Gagal Simpan', 500);
        } else {
            return response()->json('Berhasil Simpan', 200);
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
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json('Kategori Tidak Ditemukan', 404);
        }

        $data = Category::where('id', $id)->get();
        return $data;
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
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json('Kategori Tidak Ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }

        $category->name = $request->name;

        $result = $category->save();
        if (!$result) {
            return response()->json('Gagal Simpan', 500);
        } else {
            return response()->json('Berhasil Simpan', 200);
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
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json('Kategori Tidak Ditemukan', 404);
        }

        $result = $category->delete();
        if (!$result) {
            return response()->json('Gagal Hapus', 500);
        } else {
            return response()->json('Berhasil Hapus', 200);
        }
    }

    public function search(Request $request)
    {
        $value = $request->value;

        $data = Category::when(!is_array($value), function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            })->get();

        return response($data, 200);
    }

    public function rules()
    {
        $categories = $this->masterdata->getMainCategoryForValidation();

        return [
//            'code' => 'required|max:255|unique:category,code',
            'name' => 'required|max:255',
            'level' => 'required|in:Sub Category,Main Category',
            'main_category' => 'nullable|max:255|in:' . implode(',', $categories)
        ];
    }
}
