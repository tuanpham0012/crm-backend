<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('account')->group(function () {
    Route::post('/login', 'ApiUserController@login');
    Route::middleware('auth:api')->group(function () {
        Route::get('/get_info', 'ApiUserController@userInfo');
        Route::post('/change_password', 'ApiUserController@changePassword');
    });
});

Route::get('/home/get_base_data', 'HomeController@get_base_data');

Route::middleware('auth:api')->group(function () {
    //Nhân viên
    Route::prefix('staff')->group(function () {
        Route::get('/get_list', 'ApiStaffController@getStaffs');
        Route::post('/create_staff', 'ApiUserController@register');
        
    });
    // Phòng ban
    Route::post('department/staff_department', 'ApiStaffController@getStaffForDepartment');
    Route::resource('/department', 'ApiDepartmentController');
    // Khách hàng
    Route::post('/customer/assign_sale', 'ApiCustomerController@assign_sale');
    Route::post('/customer/my_customer', 'ApiCustomerController@my_list_customer');
    Route::post('/customers', 'ApiCustomerController@index');
    Route::post('/customer/search_code', 'ApiCustomerController@search_customer_code');
    Route::resource('/customer', 'ApiCustomerController');
    Route::resource('/customer_notes', 'ApiCustomerNotesController');
    // Công Việc
    Route::post('/tasks/my_task', 'TaskController@get_my_task');
    Route::post('/tasks/accept_task/{id}', 'TaskController@accept_task');
    Route::post('/tasks/add_staff', 'TaskController@add_staff');
    Route::post('/tasks/remove_staff', 'TaskController@remove_staff');
    Route::post('/tasks/update_task_status', 'TaskController@update_task_status');
    Route::post('/tasks/update_task_name', 'TaskController@update_task_name');
    Route::post('/tasks/update_customer', 'TaskController@update_customer');
    Route::resource('/tasks', 'TaskController');
    Route::resource('/task_note', 'NoteOfTaskController');
    
}); 

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
