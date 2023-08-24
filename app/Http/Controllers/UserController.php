<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function getUsers(){
        $users =User::get();
        return UserResource::collection($users);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'type'=>'required|string',
            'password'=>'required|min:5|max:12'
        ],[//رسالة الخطا
            'type.required' =>'The Name Is Required',
            'type.string' =>'The Name Is Must Be String',
            'password.required' =>'The password Is Must Be Required',
        ]);
        if ($validator->fails()){
            return $validator->messages()->all();
        }
        $user = User::create([
            'id' =>$request->id,
            'type' =>$request->type,
            'password' => Hash::make($request->password),
        ]);
        return new UserRecource($user);
    }
    public function destroy(Request $request){
        $user = User::find($request->id);
        $user->delete();
        return new UserResource($user);

    }
    public function update(Request $request,$id){
        $user = User::find($request->id);
        $user->update([
            'id'=>$request->id,
            'password'=>$request->password,
            'type'=>$request->type,
        ]);
        return new UserResource($user);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'password'=>'required|min:5|max:12',
            'type'=>'required|string',

        ],[//رسالة الخطا
            'type.required' =>'The Name Is Required',
            'type.string' =>'The Name Is Must Be String',
            'password.required' =>'The password Is Must Be Required',
        ]);
        if ($validator->fails()){
            return $validator->messages()->all();
        }
        $user =User::where('type',$request->type)->first();
        if (!$user){
            return "No User Return";
        }

        if (Hash::check($request->password,$user->passwoed)){
            return "The Password is not crrect";
        }
        $token =$user->createToken('web')->plainTextToken;
       $response =collect([
            'id' =>$user->id,
            'type'=>$user->type,
            'token'=>$token,
        ]);
        return $response;
    }

}
