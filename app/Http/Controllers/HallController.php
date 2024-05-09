<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index()
    {
        return Hall::with('user','locationCoordinates','workTime','hallCapacity','rating','hallType')->get();
    }
}
