<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use App\Models\User;
class ProfileController extends Controller
{
    public function index()
	{ 
		return view('user.profile.index');
	}   

	public function update(request $request)
	{
		$this->validate($request,[
			'name'=>'required',
			'phone'=>'required|numeric|min:10',
		]);
		$data=Auth::user();
		$data->name=$request->name;
		$data->email=$request->email_id;
		$data->save();
		session()->flash('success_msg','Profile has been Updated successfully');
		return redirect('user/profile');
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
			session()->flash('success_msg','Password has been Updated successfully');
			return back();
		}
		else{
			session()->flash('danger_msg','Oops!! Something went wrong');
			return back();
		}
	}
}
