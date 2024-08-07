<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoodCategoryController extends Controller
{
    public function index()
    {

        $categories = FoodCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category = FoodCategory::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {

        $category = FoodCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category = FoodCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {

        $category = FoodCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
