<?php

namespace App\Http\Controllers;

use App\Models\DecorationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DecorationCategoryController extends Controller
{
    public function index()
    {

        $categories = DecorationCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:decoration_categories,type'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = DecorationCategory::create($validator->validated());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = DecorationCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|unique:decoration_categories,type,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = DecorationCategory::findOrFail($id);
        $category->update($validator->validated());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = DecorationCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
