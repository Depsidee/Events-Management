<?php

namespace App\Http\Controllers;

use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HallViewController extends Controller
{
    public function index()
    {
        $views = View::with(['user', 'hall'])->get();
        return response()->json($views);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'hall_id' => 'required|exists:halls,id',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $view = View::create($validator->validated());
        return response()->json($view, 201);
    }

    public function show($id)
    {
        $view = View::with(['user', 'hall'])->findOrFail($id);
        return response()->json($view);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'hall_id' => 'required|exists:halls,id',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $view = View::findOrFail($id);
        $view->update($validator->validated());
        return response()->json($view);
    }

    public function destroy($id)
    {

        $view = View::findOrFail($id);
        $view->delete();
        return response()->json(null, 204);
    }
}
