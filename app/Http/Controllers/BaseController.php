<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller

    {
        public function sendResponse($result , $messege){
            $response =[
                'success'=> true,
                'data'=> $result,
                'messege'=> $messege,
            ];
            return response()->json($response ,200);
        }
        public function sendError($error , $errorMessege=[] ,$code =404){
            $response =[
                'success'=> false,
                'data'=> $error,
            ];
            if (!empty($errorMessege)) {
                $response['data']=$errorMessege;
            }
            return response()->json($response ,$code);
        }


    }
