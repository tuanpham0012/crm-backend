<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiChangePasswordRequest;


class ApiUserController extends Controller
{
    public function register(ApiRegisterRequest $request){
        $user = new User();
        $user->fill($request->all());
        $user->employee_code = Str::orderedUuid();
        $user->password = Hash::make("$request->password");
        $user->save();
        return response()->json($user);
    }

    public function login(ApiLoginRequest $request){
        $user = User::with('role')->whereEmail($request->email)->first();
        if($user){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user->token = $user->createToken('App')->accessToken;
                return response()->json($user);
            }else{
                return response()->json(['message' => 'Sai mật khẩu!'],401);
            }
        }else{
            return response()->json(['message' => 'Tài khoản không tồn tại!'],404);
        }
        
    }

    public function changePassword(ApiChangePasswordRequest $request){
        $user = User::whereEmail($request->email)->first();
        if($user){
            if(Hash::check($request->password, Auth::user()->password)){
                if($request->newPassword === $request->passwordConfirm){
                    $user->update(['password' => Hash::make($request->newPassword)]);  
                    return response()->json(['message' => 'Đổi mật khẩu thành công!'], 200);
                }else{
                    return response()->json(['message' => 'Đổi mật khẩu không thành công! Mật khẩu mới không trùng khớp!'], 401);
                }
            }else{
                return response()->json(['message' => 'Sai mật khẩu!'], 401);
            }
        }else{
            return response()->json(['message' => 'Không tìm thấy tài khoản!'], 401);
        }
        
    }

    public function userInfo(Request $request){
        return response()->json($request->user('api')->load('role'));
    }
}
