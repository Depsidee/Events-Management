<?php

namespace App\Http\Controllers;

use App\Models\FoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoodRequestController extends Controller
{
    public function index()
    {
        $foodRequests = FoodRequest::with(['reservation', 'food'])->get();
        return response()->json($foodRequests);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id',
            'food_id' => 'required|exists:food,id',
            'amount' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $foodRequest = FoodRequest::create($validator->validated());
        return response()->json($foodRequest, 201);
    }

    public function show($id)
    {

        $foodRequest = FoodRequest::with(['reservation', 'food'])->findOrFail($id);
        return response()->json($foodRequest);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id',
            'food_id' => 'required|exists:food,id',
            'amount' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $foodRequest = FoodRequest::findOrFail($id);
        $foodRequest->update($validator->validated());
        return response()->json($foodRequest);
    }

    public function destroy($id)
    {

        $foodRequest = FoodRequest::findOrFail($id);
        $foodRequest->delete();
        return response()->json(null, 204);
    }
}
