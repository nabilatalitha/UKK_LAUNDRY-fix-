<?php

namespace App\Http\Controllers;


use App\Models\Detail_transaksi;
use App\Models\Transaksi;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class Detail_transaksiController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required',
            'id_paket' => 'required',
            'qty' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $detail = new Detail_transaksi();
        $detail->id_transaksi = $request->id_transaksi;
        $detail->id_paket = $request->id_paket;

        //GET HARGA PAKET
        $paket = Paket::where('id_paket', '=', $detail->id_paket)->first();
        $harga = $paket->harga;

        $detail->qty = $request->qty;
        $detail->subtotal = $detail->qty * $harga;

        $detail->save();

        $data = Detail_transaksi::where('id_paket', '=', $detail->id_paket)->first();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah detail transaksi', 
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        //untuk ambil detil dari transaksi tertentu

        $data = DB::table('detail_transaksi')->join('paket', 'detail_transaksi.id_paket', 'paket.id_paket')
                                            ->select('detail_transaksi.*', 'paket.jenis')
                                            ->where('detail_transaksi.id_transaksi', '=', $id)
                                            ->get();
        return response()->json($data);                        
    }

    public function getTotal($id)
    {
        $total = Detail_transaksi::where('id_transaksi', $id)->sum('subtotal');
        
        return response()->json([
            'total' => $total
        ]);
    }
}