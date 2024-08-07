<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('category')->get();
        return response()->json($foods);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'food_category_id' => 'required|exists:food_categories,id',
            'image' => 'required|image',
            'price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //store image
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.decoration.' . $extension;
        $path = 'decoration/image';
        $file->move($path, $fileName);
        $path = $path . $fileName;

        $food = Food::create([
            'food_category_id' => $request->decoration_category_id,
            'image' => $path,
            'price' => $request->price
        ]);
        return response()->json($food, 201);
    }

    public function show($id)
    {

        $food = Food::with('category')->findOrFail($id);
        return response()->json($food);
    }

    public function update(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'food_category_id' => 'required|exists:food_categories,id',
            'price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $food = Food::findOrFail($id);

        $food->update([
            'decoration_category_id' => $request->food_category_id,
            'price' => $request->price
        ]);
        return response()->json($food);
    }

    public function destroy($id)
    {

        $food = Food::findOrFail($id);
        $food->delete();
        return response()->json(null, 204);
    }
}
