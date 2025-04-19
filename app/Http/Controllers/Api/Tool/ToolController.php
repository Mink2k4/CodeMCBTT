<?php

namespace App\Http\Controllers\Api\Tool;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    public function __construct()
    {
        $this->middleware('xss');
    }

    public function getUid(Request $request)
    {
        $valid = Validator::make($request->all(),[
            'link' => 'required|url'
        ]);

        if($valid->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $valid->errors()->first()
            ]);
        }else{
            $data = getUid($request->link);
            if(isset($data)){
                if($data['status'] == true){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy UID thành công',
                        'uid' => $data['id']
                    ]);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không thể lấy UID'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể lấy UID'
                ]);
            }
        }
    }
}
