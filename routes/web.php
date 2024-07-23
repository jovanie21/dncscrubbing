<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminScrubController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\DncList;
use App\Models\RegionType;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () {
	Artisan::call('config:cache');
});
Route::get('test11', function () {
	Artisan::call('cache:clear');
});
/*
Route::get('/', function () {
	 return redirect('')->route('login');
});*/


Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::post('/contact','App\Http\Controllers\HomeController@contact');
//Route::post('/contact',[App\Http\Controllers\HomeController::class, 'contact']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/checknumber/{token}', [App\Http\Controllers\HomeController::class, 'checknumber']);
Route::get('/autoProcess/{token}', [App\Http\Controllers\HomeController::class, 'autoProcess']);
Route::get('/insertnumber/{number}/{token}/{type}', [App\Http\Controllers\HomeController::class, 'insertnumber']);
Route::get('/deletenumber/{number}/{token}', [App\Http\Controllers\HomeController::class, 'deletenumber']);
Route::get('/autoProcessScrub/{token}/{limit}', [App\Http\Controllers\HomeController::class, 'autoProcessScrub']);
Route::get('/autoProcessScrubUser/{token}/{limit}', [App\Http\Controllers\HomeController::class, 'autoProcessScrubUser']);
Route::get('/updateUploadFlag/{userScrubId}/{flag}', [App\Http\Controllers\HomeController::class, 'updateUploadFlag']);
//API for deleting Old scrubbed data
//Route::get('/deleteOldData/{token}',[App\Http\Controllers\HomeController::class, 'deleteOldData']);

/* scrub-dump */
Route::get('scrub-dump',[App\Http\Controllers\admin\AdminScrubController::class, 'scrubdump']);
Route::get('user/scrub-dump',[App\Http\Controllers\user\ScrubController::class, 'scrubdump']);
Route::get('users/pdf/{id}',[App\Http\Controllers\user\ScrubController::class, 'pdf'])->middleware('auth');
/* scrub-dump */
/* send-mail */
Route::get('send-mail',[App\Http\Controllers\admin\AdminScrubController::class, 'sendmail']);
/* send-mail */

Route::get('/deleteOldData/{token}', [App\Http\Controllers\admin\AdminScrubController::class, 'deleteOldData']);
Route::group(['middleware'=>['role:admin','auth'],'namespace'=>'App\Http\Controllers\admin','prefix'=>'admin'],
	function(){
        Route::post('/upload/dnc','HomeController@uploadadminfile');
		Route::get('/home','HomeController@index');
		Route::get('/dnclist','HomeController@dnclist');
		Route::get('/getData','HomeController@getData');
		Route::Post('/findnumber','HomeController@findnumber');
		Route::Post('/findpage','HomeController@findpage');

		Route::get('/getcontactdata','HomeController@getcontactdata');
		Route::get('/viewcontact','HomeController@viewcontact');
		Route::get('/displaycontact','HomeController@displaycontact');
		Route::get('/GetUserList','HomeController@GetUserList');
		Route::get('/uploadadmin','HomeController@uploadadmin');
		Route::Post('/uploadadminfile','HomeController@uploadCSV');
		Route::get('/processabulkdminfile/{id}','HomeController@processabulkdminfile');
		Route::get('/processadminfile/{id}','HomeController@processadminfile');
		Route::get('/displaycontact/{id}','HomeController@displaycontact');
		Route::get('/deleteadminfile/{id}','HomeController@deleteadminfile');
		Route::get('/alldeleteadminfile','HomeController@alldeleteadminfile');
		Route::get('/userlist','HomeController@userlist');

		//Users
		Route::get('user/deactivatetoken/{id}', 'UserController@deactivatetoken');
		Route::get('user/generateToken/{id}', 'UserController@generateToken');
		Route::get('user/getData', 'UserController@getData');
		Route::get('user/changepassword/{id}', 'UserController@changepassword');
		Route::post('user/updatepassword/{id}', 'UserController@updatepassword');
		Route::Resource('user', 'UserController');
		Route::Resource('company', 'CompanyController');

				//profile
		Route::get('profile','ProfileController@index');
		Route::post('profile/update','ProfileController@update');
		Route::post('profile/updatepassword','ProfileController@updatepassword');
		//change pass company 
		Route::get('company/changepassword/{id}','CompanyController@changepasswordindex')->name('changepass');
		Route::post('company/changepassword/{id}','CompanyController@changepassword')->name('chnage.password');


		//scrub
		Route::get('scrub-upload','AdminScrubController@scrubupload');
		Route::get('/admin/scrub/file/uploaded', [AdminScrubController::class, 'fileUploaded'])->name('admin.scrub.file.uploaded');
		Route::POST('scrub-upload/fillter','AdminScrubController@dnclistfillter')->name('dnc.fillter');
		Route::GET('/zip/{active?}/{inactive?}/{invalid?}', 'AdminScrubController@zipdownload')->name('zip');

		Route::POST('/storescrubfile','AdminScrubController@seperateList');
		Route::get('/genratepdf/{id}','AdminScrubController@genratepdf');
		Route::get('/pdf/{id}','AdminScrubController@getPDF');
		Route::get('/processscrubfile/{id}','AdminScrubController@processscrubfile');
		//	Route::get('/deleteData/{token}','AdminScrubController@deleteData');


		Route::get('/userscrublist','HomeController@userscrublist');
		Route::get('dropbox/{id}/download',[AdminScrubController::class,'downloadOriginal'])->name('dropbox.download');


	});




Route::group(['middleware'=>['role:user','auth'],'namespace'=>'App\Http\Controllers\user','prefix'=>'user'],
	function(){
		Route::get('/home','HomeController@index');
		Route::get('/tokendetail','HomeController@tokendetail');
		Route::get('/dnclist','HomeController@dncuserlist');
		Route::get('/getData','HomeController@getData');
		Route::get('/upload','HomeController@upload');
		Route::get('/processfile/{id}','HomeController@processfile');
		Route::get('/processbulkfile/{id}','HomeController@processbulkfile');
		Route::get('/deletefile/{id}','HomeController@deletefile');
		Route::Post('/uploaduserfile','HomeController@uploaduserfile');
		Route::Post('/check','HomeController@check');
		Route::Post('/findpage','HomeController@findpage');

			//profile
		Route::get('profile','ProfileController@index');
		Route::post('profile/update','ProfileController@update');
		Route::post('profile/updatepassword','ProfileController@updatepassword');

				//scrub
		Route::get('/userscrub-upload','ScrubController@scrubupload');
		Route::get('/upload','HomeController@uploaduser');
		Route::Post('/uploaduserfile','HomeController@uploadCSV');
		// Route::get('/userdnclist','HomeController@userdnclist');
		
		// Route::POST('/userstorescrubfile','ScrubController@userstorescrubfile');
		Route::POST('/userstorescrubfile','ScrubController@seperateList');
		Route::get('/processscrubfile/{id}','ScrubController@processscrubfile');
		Route::get('/pdf/{id}','ScrubController@pdf');

	});


	Route::get('regions', function() {
		$this->data['regions'] = ['internal', 'federal', 'litigator', 'wireless'];
		
		$q = DncList::select(['phone_no as phone', 'federal', 'litigator', 'internal', 'wireless']);

        $this->rowData  = [
            'internal' => 'yes',
        	'wireless' => 'yes',
            'federal' => 'yes',
            'litigator' => 'yes',
		];

        if (!empty($this->rowData) && isset($this->rowData)) {
            // if selected scrub types are more then one then we added orWhere clause to query like, (interel = true || federal  = false) liek that
            if (count($this->rowData) > 1) {
                $q = $q->where(function ($query) {
                    return $query->orwhere($this->rowData);
                });
            } else {

                // in else part if user select only one scrub type then we adeed where clause onyl like internal/federal/wireless/etc = true
                $q = $q->where($this->rowData);
            }
        }
		$this->data['is_region'] = true;

        // add where in clause if user is selected specific dnc list from dropdown
        if ($this->data['is_region']) {
            $q = $q->whereIn('region_id', array_keys($this->data['regions']));
        } else {
            // if select any scrub type then get all regions id from regions table by type that joing with regions_types table
            // and add where in clouse
            $regionIds = RegionType::select('region_id')->whereIn('type', array_keys($this->data['regions']))->pluck('region_id')->toArray();
            
            $q = $q->whereIn('region_id', $regionIds);
        }
		

		dd($q->toSql());
	});