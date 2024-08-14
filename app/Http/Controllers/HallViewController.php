<?php

namespace App\Http\Controllers;

use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HallViewController extends Controller
{
//////////////////
//index
/////////
    public function index()
    {
        $views = View::with(['user', 'hall'])->get();
        return response()->json($views);
    }
///////////////
//store
//////////////
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
///////////////
//show
/////
    public function show($id)
    {

        $view = View::find($id);
if($view->user_id!=Auth::user()->id){
    return response(['don\'t have permission to fetch this data'],403);
}

        return response()->json($view);
    }
////////////////
///update view
///////////
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
////////////////////
//delete view
/////////////////
    public function destroy($id)
    {

        $view = View::findOrFail($id);
        $view->delete();
        return response()->json(null, 204);
    }
}
