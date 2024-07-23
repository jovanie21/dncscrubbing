<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersWithCompanyDetails = User::join('user_details', 'users.id', '=', 'user_details.user_id')
        ->where('users.name', '=', 'company')
        ->where('users.is_active', '=', 1)
        ->orderByDesc('users.created_at')
        ->select('users.*', 'user_details.*')
        ->get();
       return view('admin.company.index',compact('usersWithCompanyDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.company.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token = md5(microtime());
        $this->validate($request,[
            'email'=>'required|unique:users,email',
            'phone'=>'required',
            'company_name'=>'required',
            'company_zip'=>'required',
            'company_address'=>'required',
            
        ]);
        $company=new User;
        $company->name="Company";
        $company->email=$request->email;
        $company->remember_token = $token;
        $company->assignRole('user');
        $company->save();

        $userdetail=new UserDetail;
        $userdetail->user_id=$company->id;
        $userdetail->phone_number=$request->phone;
        $userdetail->company_name=$request->company_name;
        $userdetail->company_zip=$request->company_zip;
        $userdetail->company_address=$request->company_address;
        $userdetail->save();

        \Mail::send('mail.passwordReset', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        session()->flash('success_msg','Company has been Added successfully');
        return redirect()->route('company.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companyEdit = User::join('user_details', 'users.id', '=', 'user_details.user_id')
        ->where('users.id', '=', $id)
        ->first();
        return view('admin.company.edit',compact('companyEdit'));

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
            'email'=>['required',
            Rule::unique('users', 'email')->ignore($id),
        ],
            'phone'=>'required',
            'company_name'=>'required',
            'company_zip'=>'required',
            'company_address'=>'required',
        ]);

        $companyUpdate = User::where('id',$id)->update([
            'name' => "Company",
            'email' => $request->email,
        ]);
        $companydetailsUpdate = UserDetail::where('user_id',$id)->update([
            'phone_number'=>$request->phone,
            'company_name'=>$request->company_name,
            'company_zip'=>$request->company_zip,
            'company_address'=>$request->company_address,
        ]);
        session()->flash('success_msg','Company has been updated successfully');
        return redirect()->route('company.index');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = User::where('id',$id)->update([
            'is_active'=> 2,
        ]);
        if ($company) {
           
            return new JsonResponse(['message' => 'Company deleted successfully'], 200);
        } else {
            return new JsonResponse(['message' => 'Company not found'], 404);
        }
    }
    public function changepasswordindex($id){
        return view('admin.company.changepassword',compact('id'));
    }
    public function changepassword(Request $request,$id){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        $user = User::where('id', $id)->update([
            'password' => bcrypt($request->password),
            'actual_password'=> $request->password,
            'remember_token' => null, 
        ]);
        session()->flash('success_msg','Company Password has been updated successfully');
        return redirect()->route('company.index');  
    }
    
}
 