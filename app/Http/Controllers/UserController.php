<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nama_user' => 'required',
			'username' => 'required',
			'password' => 'required|string|min:6',
			'role' => 'required',
			'id_outlet' => 'required'
		]);

		if($validator->fails()){
            return response()->json($validator->errors());
		}

		$user = new User();
		$user->nama_user 	= $request->nama_user;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role 	= $request->role;
		$user->id_outlet = $request->id_outlet;

		$user->save();

		$token = JWTAuth::fromUser($user);

        $data = User::where('username','=', $request->username)->first();
        return response()->json([
			'success' => true,
            'message' => 'Data user berhasil ditambahkan',
            'data' => $data
        ]);
	}

	public function getAll()
  	{

		$data = DB::table('user')->join('outlet', 'user.id_outlet', 'outlet.id_outlet')
									->select('user.*', 'outlet.*')
									->orderBy('id_user')
									->get();

      	return response()->json(['data' => $data]);
  	}

  	public function getById($id_user)
  	{
      	$data['users'] = User::where('id_user', '=', $id_user)->first();

      	return response()->json(['data' => $data]);
  	}

  	public function update(Request $request, $id_user)
  	{
    	$validator = Validator::make($request->all(),[
			'nama_user'  => 'required|string',
			'role'=>'required|string',
			'id_outlet' => 'required'
    	]);

    	if ($validator->fails()) {
      	return response()->json($validator->errors());
    	}

    	$user = User::where('id_user', '=', $id_user)->first();
		$user->nama_user	= $request->nama_user;
		$user->username = $request->username;
		$user->role 	= $request->role;
		$user->id_outlet = $request->id_outlet;
		if($request->password != null) {
			$user->password = Hash::make($request->password);
		}

    	$user->save();

    	return Response()->json(['message' => 'Data user berhasil diubah',]);
  	}

  	public function delete($id_user)
  	{
      	$delete = User::where('id_user', '=', $id_user)->delete();

      	if ($delete) {
          	return response()->json(['message' => 'Data user berhasil dihapus']);
      	}else {
          	return response()->json(['message' => 'Data user gagal dihapus']);
      	}
  	}
}