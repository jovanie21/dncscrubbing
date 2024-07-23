<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use DB;
use App\Models\TokenExpiery;
use Yajra\Datatables\Datatables;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $random_string = md5(microtime());
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users',
            'phone'=>'required',
            'company_name'=>'required',
            'company_zip'=>'required',
            'company_address'=>'required',
            'password'=>'required',
        ]);
        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->actual_password=$request->password;
        $user->password=bcrypt($request->password);
        $user->assignRole('user');
        $user->save();

        $userdetail=new UserDetail;
        $userdetail->user_id=$user->id;
        $userdetail->phone_number=$request->phone;
        $userdetail->company_name=$request->company_name;
        $userdetail->company_zip=$request->company_zip;
        $userdetail->company_address=$request->company_address;
        $userdetail->save();

        $usertoken=new TokenExpiery;
        $usertoken->user_id=$user->id;
        $usertoken->token=$random_string;
        $usertoken->status='1';
        $usertoken->save();


        session()->flash('success_msg','User has been Added successfully');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users=User::find($id);
        $toekndetails=TokenExpiery::where('user_id',$id)->orderBy('id','DESC')->get();
        return view('admin.users.view',compact('toekndetails','users'));       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row=User::find($id);
        $userdetail=UserDetail::where('user_id',$id)->first();
        return view('admin.users.edit',compact('row','userdetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $this->validate($request,[
        'name'=>'required',
        'email'=>'required',
        'phone'=>'required',
        'company_name'=>'required',
        'company_zip'=>'required',
        'company_address'=>'required',
    ]);
       $user=User::find($id);
       $user->name=$request->name;
       $user->email=$request->email;
         // $user->actual_password=$request->password;
         // $user->password=bcrypt($request->password);
       $user->save();

       $userdetail=UserDetail::where('user_id',$id)->first();
       $userdetail->phone_number=$request->phone;
       $userdetail->company_name=$request->company_name;
       $userdetail->company_zip=$request->company_zip;
       $userdetail->company_address=$request->company_address;
       $userdetail->save();
       session()->flash('success_msg','User has been updated successfully');
       return redirect()->route('user.index');
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row=User::find($id);
        $row->is_active='2';
        $row->save();
        return  back();
    }


    public function getData(Request $request)
    {

     DB::statement(DB::raw('set @rownum=0'));
     $query = User::join('user_details','users.id','user_details.user_id')->whereHas('roles', function ($q) {
        $q->where('name', 'user');
    })->select([
       DB::raw('@rownum  := @rownum  + 1 AS rownum'),
       DB::raw('users.id as id'),
       'users.name',
       'users.email',
       'users.actual_password',
       'user_details.phone_number',
       'user_details.company_name',
       'user_details.company_address',
       'user_details.company_zip',
       'user_details.created_at',
       'user_details.updated_at',
   ])->where('is_active',1);
    return Datatables::of($query)      
          ->editColumn('created_at', function ($datatables) {
                return date('d-M-Y h:i:s A', strtotime($datatables->created_at));
            })  ->editColumn('updated_at', function ($datatables) {
                return date('d-M-Y h:i:s A', strtotime($datatables->updated_at));
            })
    ->addColumn('action', function ($datatables) {
        return '<a href="'.route('user.edit',$datatables->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>&nbsp&nbsp
        <button class="btn btn-xs btn-danger" onclick="deleteit('.$datatables->id.')"><i class="glyphicon glyphicon-trash"></i> Delete</button> &nbsp;&nbsp<a href="'.route('user.show',$datatables->id).'" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> View Token</a>&nbsp;&nbsp<a href="'.url('admin/user/changepassword',$datatables->id).'" class="btn btn-xs btn-info"><i class="fa fa-key"></i> Change Password</a>';
    })
    ->rawColumns(['action'])->make(true);
}

public function generateToken($id){    
    $random_string = md5(microtime());
    $usertoken=new TokenExpiery;
    $usertoken->user_id=$id;
    $usertoken->token=$random_string;
    $usertoken->status='1';
    $usertoken->save();
    session()->flash('success_msg','User Token has been Generated successfully');
    return back();

}

public function deactivatetoken($id){    
    $usertoken= TokenExpiery::find($id);
    $usertoken->status='2';
    $usertoken->save();
    session()->flash('warning_msg','User Token has been Deleted successfully');
    return back();

}


public function changepassword($id){
    return view('admin.users.changepassword',compact('id'));
}


public function updatepassword(Request $request,$id){

    $this->validate($request, [
            'password'=>'required|min:6|confirmed',
        ]);
     $actual=User::find($id);
            $actual->actual_password=$request->password;
            $actual->password=bcrypt($request->password);
            $actual->save();
           session()->flash('success_msg','Password has been Updated');
          return redirect()->route('user.index');
}
}