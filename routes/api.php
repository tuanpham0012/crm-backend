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
    Route::post('/staffs/remove_multiple', 'StaffController@remove_multiple');
    Route::post('/staffs/update_department', 'StaffController@update_department');
    Route::post('/staffs', 'StaffController@index');
    Route::resource('/staff', 'StaffController');
    // Phòng ban
    Route::post('department/add_staff', 'ApiDepartmentController@add_staff');
    Route::post('department/update_position/{id}', 'ApiDepartmentController@update_position');
    Route::post('department/remove_position/{id}', 'ApiDepartmentController@remove_staff');
    Route::post('department/staff_department', 'ApiStaffController@getStaffForDepartment');
    Route::resource('/department', 'ApiDepartmentController');
    // Khách hàng
    Route::post('/customer/multiple_create', 'ApiCustomerController@create_customer_excel');
    
    Route::post('/customer/assign_sale', 'ApiCustomerController@assign_sale');
    Route::post('/customer/update_type', 'ApiCustomerController@update_type');
    Route::post('/customer/multiple_delete', 'ApiCustomerController@multiple_delete');
    Route::post('/customer/my_customer', 'ApiCustomerController@my_list_customer');
    Route::post('/customers', 'ApiCustomerController@index');
    Route::post('/customer/search_code', 'ApiCustomerController@search_customer_code');
    Route::resource('/customer', 'ApiCustomerController');
    Route::resource('/customer_notes', 'ApiCustomerNotesController');
    Route::resource('/contact', 'CustomerCallHistoryController');
    // Công Việc
    Route::post('/tasks/my_task', 'TaskController@get_my_task');
    Route::post('/tasks/accept_task/{id}', 'TaskController@accept_task');
    Route::post('/tasks/add_staff', 'TaskController@add_staff');
    Route::post('/tasks/remove_staff', 'TaskController@remove_staff');
    Route::post('/tasks/update_task_status', 'TaskController@update_task_status');
    Route::post('/tasks/update_task_name', 'TaskController@update_task_name');
    Route::post('/tasks/update_task_content', 'TaskController@update_task_content');
    Route::post('/tasks/update_customer', 'TaskController@update_customer');
    Route::resource('/tasks', 'TaskController');
    Route::resource('/task_note', 'NoteOfTaskController');

    //Dự án
    Route::post('/projects/join', 'ProjectsController@join_project');
    Route::post('/projects/add_participant', 'ProjectsController@add_participant');
    Route::post('/projects/confirm_participant', 'ProjectsController@confirm_participant');
    Route::post('/projects/delete_participant', 'ProjectsController@delete_participant');
    Route::resource('/projects', 'ProjectsController');

    // Thông báo

    Route::post('/notification/get_list', 'NotificationController@get_list_notification');
    Route::post('/notification/read_all', 'NotificationController@read_all');
    Route::resource('/notification', 'NotificationController');
    

    //Gửi mail
    Route::post('/send_mail', 'SendMailController@send_mail');
    Route::post('send_mail_multiple_customer', 'ApiCustomerController@send_mail');

}); 

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
