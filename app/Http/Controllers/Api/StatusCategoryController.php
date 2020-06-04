<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\StatusCategory;
use Illuminate\Http\Request;

class StatusCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = StatusCategory::with('status', 'category')
            ->when($keyword = $request->get('category_code'), function ($query) use ($keyword) {
                $query->where('category_code', $keyword);
            })->get();

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\StatusCategory $statusCategory
     * @return \Illuminate\Http\Response
     */
    public function show(StatusCategory $statusCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\StatusCategory $statusCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatusCategory $statusCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\StatusCategory $statusCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusCategory $statusCategory)
    {
        //
    }
}
