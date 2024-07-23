<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use App\Models\User;
class ProfileController extends Controller
{
    public function index()
    {
        $user=Auth::user();
        // dd($user);
       return view('admin.profile.index',compact('user'));
    }

    public function update(request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required|numeric',
        ]);
        $data=Auth::user();
        $data->name=$request->name;
        $data->phone=$request->phone;
        $data->save();
         session()->flash('success_msg','profile has been Updated');
       return redirect('admin/profile');
    }

    public function updatepassword(Request $request){
        $this->validate($request, [
            'oldpassword'=>'required',
            'password'=>'required|min:6|confirmed',
        ]);
        $user=Auth::user();
        $oldpassword=$user->password;
        if(Hash::check($request->oldpassword,$oldpassword)){
            $user->fill([
                'password'=>bcrypt($request->password),
            ])->save();

            $actual=User::find($user->id);
            $actual->actual_password=$request->password;
            $actual->save();


            session()->flash('success_msg','Password has been Updated');
            return back();
        }
        else{
            session()->flash('danger_msg','Oops!! Something went wrong');
             return back();
        }

    }
}
