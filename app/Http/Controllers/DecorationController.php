<?php

namespace App\Http\Controllers;

use App\Models\Decoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DecorationController extends Controller
{
    public function index()
    {

        $decorations = Decoration::with('decorationCategory')->get();
        return response()->json($decorations);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'decoration_category_id' => 'required|exists:decoration_categories,id',
            'image' => 'required|image',
            'price' => 'required|numeric|min:0'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //store image
        $file= $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'.decoration.'.$extension;
        $path = 'decoration/image';
        $file->move($path,$fileName);
        $path = $path.$fileName;


        $decoration = Decoration::create([
            'decoration_category_id' => $request->decoration_category_id,
            'image' => $path,
            'price' => $request->price
            ]);

        return response()->json($decoration, 201);
    }
////////////
    public function show($id)
    {

        $decoration = Decoration::with('decorationCategory')->findOrFail($id);
        return response()->json($decoration);
    }
///////////////////
public function update(Request $request, $id)
{


    $validator = Validator::make($request->all(), [
        'decoration_category_id' => 'required|exists:decoration_categories,id',
        'price' => 'required|numeric|min:0'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $decoration = Decoration::findOrFail($id);
    $decoration->update([
        'decoration_category_id' => $request->decoration_category_id,
        'price' => $request->price
        ]
    );
    return response()->json($decoration);
}///////////////////

    public function destroy($id)
    {

        $decoration = Decoration::findOrFail($id);
        $decoration->delete();
        return response()->json(null, 204);
    }
}
