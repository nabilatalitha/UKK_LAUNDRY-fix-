<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Hash;
use App\Models\Outlet;
use JWTAuth;

class OutletController extends Controller
{
    public $user;
    public function __construct(){
        //$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)     
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string',
            'alamat' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
		}

        $outlet = new Outlet();
        $outlet->nama_outlet = $request -> nama_outlet;
        $outlet->alamat = $request -> alamat;

        $outlet->save();
        $data = Outlet::where('id_outlet', '=', $outlet->id_outlet)->first();

        return response()->json([
            'message' => 'Data outlet berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getAll($limit = NULL, $offset = NULL)
    {        
        $data = Outlet::get();
        return response()->json(['data' => $data]);
    }
    
    public function getById($id_outlet)
    {
        $data = Outlet::where('id_outlet', '=', $id_outlet)->first();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, $id_outlet)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string',
            'alamat' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $outlet = Outlet::where('id_outlet', '=', $id_outlet)->first();
        $outlet->nama_outlet = $request -> nama_outlet;
        $outlet->alamat = $request -> alamat;

        $outlet->save();

        return response()->json([
            'success' => true,
            'message' => "Data outlet berhasil diubah"
        ]);
    }

    public function delete($id)
    {
        $delete = Outlet::where('id_outlet', '=', $id)->delete();

        if($delete) {
            return response()->json([
                'success' => true,
                'message' => "Data outlet berhasil dihapus"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Data outlet gagal dihapus"
            ]);
        }
    }
}