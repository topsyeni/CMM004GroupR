<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createUser(){
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->company_name = auth('api')->user()->company_name;
        $user->role = "user";
        $user->password = bcrypt(request()->password);
        $user->save();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $user
        ], 200);
    }

    public function updateUser($id = null){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'code'      => 404,
                'status'    => 'failed',
                'message'   => 'User not found'
            ], 404);
        }
        if($user->company_name != auth()->user()->company_name)
            return response()->json([
                'code'      => 401,
                'status'    => 'unauthorized',
                'message'   => 'You are not authorized to perform this action'
            ], 404);

        $validator = Validator::make(request()->all(), [
            'email' => 'email|unique:users',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if(isset(request()->name) && !empty(request()->name)) $user->name = request()->name;
        if(isset(request()->email) && !empty(request()->email)) $user->email = request()->email;
        $user->save();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'data'=> $user
        ], 200);
    }

    public function deleteUser($id = null){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'code'      => 404,
                'status'    => 'failed',
                'message'   => 'User not found'
            ], 404);
        }
        if($user->company_name != auth()->user()->company_name)
            return response()->json([
                'code'      => 401,
                'status'    => 'unauthorized',
                'message'   => 'You are not authorized to perform this action'
            ], 404);

        if($user->role == 'admin')
            return response()->json([
                'code'      => 400,
                'status'    => 'failed',
                'message'   => 'You cannot delete an admin account'
            ], 404);

        $user->delete();

        return response()->json([
            'code'=> '200',
            'status'=> 'success',
            'message'=> 'User deletion successful'
        ], 200);
    }

    public function getUsers(Request $request){
        $page_size = ($request->page_size ?? 10);
        $page_num = ($request->page_num ?? 1);
        $sort_by = ($request->sort_by ?? 'id');
        $sort_type = ($request->sort_type ?? 'ASC');
        $name = ($request->name ?? '');

        $users = User::where('company_name', auth()->user()->company_name)
            ->where(function ($query) use ($name){
                $query->whereRaw('name LIKE "%'. $name . '%"')
                    ->orWhereRaw('email LIKE "%'. $name . '%"');
            })
            ->orderBy($sort_by, $sort_type)
            ->paginate($page_size, ['*'], 'page', $page_num);

        return response()->json([
            'status'=>'success',
            'message'=>'Users fetched successfully',
            'data' => $users
        ], 200);
    }
}
