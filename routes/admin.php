
<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TmController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FamiliesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\TemplateCategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PageManagerController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\StatesController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\TehsilsController;
use App\Http\Controllers\Admin\VillageController;
use App\Http\Controllers\Admin\PanchayatController;



use Illuminate\Support\Facades\Route;



Route::get('/test', function () {
    return view('email.registration_mail');
}); 



//Forget and reset password  admin and web 
Route::controller(AuthController::class)->group(function () {
    Route::get('forget-password', 'forgetPassword')->name('forget.password');
    Route::post('send-link', 'sendLink')->name('sendLink');
    Route::get('reset-password/{token}', 'resetPassword')->name('password.reset');
    Route::post('password-update', 'updatePassword')->name('password.update');
});





// Auth Routing
Route::prefix('admin/')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('', 'login');
        Route::get('login', 'login')->name('admin.login');
        Route::post('do_login', 'do_login')->name('admin.do_login');
    });


 Route::get('send_invoice{id?}', [PaymentsController::class, 'send_invoice'])->name('payments.send_invoice');


 Route::group(['middleware' => 'auth'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('logout', 'logout')->name('admin.logout');
        });



        //Admin Common functions and method
        Route::controller(HomeController::class)->group(function () {
            Route::post('theme_style', 'theme_style')->name('theme_style');
            Route::get('dashboard', 'index')->name('admin.index');
            Route::get('get_chart_data', 'get_chart_data')->name('admin.get_chart_data');
            Route::get('get_member_chart_data', 'get_member_chart_data')->name('admin.get_member_chart_data');

            
            Route::get('setting', 'setting')->name('admin.setting');
            Route::get('changePassword-form', 'changePassword')->name('admin.changePassword.form');
            Route::post('update-password', 'updatePassword')->name('admin.updatePassword');


            Route::get('setting-form', 'setting')->name('admin.setting.form');
            Route::post('update-setting', 'updateSetting')->name('admin.update.setting');





            // PROFILE SECTION ROUTE 
            Route::get('profile', 'profile')->name('admin.profile');
            Route::Post('profile/update', 'updateProfile')->name('profile.update');
           
        });





  


     


        //Offer Management Routing
        Route::controller(NotificationController::class)->prefix('notification')->group(function () {
            Route::get('index', 'index')->name('notification.index');
            Route::get('form/{id?}', 'form')->name('notification.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('notification.manage');
            Route::get('view{id?}', 'view')->name('notification.view');
            Route::get('status', 'status')->name('notification.status');
            Route::get('delete/{id}', 'delete')->name('notification.delete');
        });



        /************************************ MASTER MODULE ROUTING START *********************************/

        // Role Controller Management Routing
        Route::controller(RoleController::class)->prefix('role')->group(function () {
            Route::get('index', 'index')->name('role.index');
            Route::get('add', 'add')->name('role.add');
            Route::Post('create', 'create')->name('role.create');
            Route::get('edit/{id}', 'edit')->name('role.edit');
            Route::Post('update', 'update')->name('role.update');
        });


        // Permission Management Routing
        Route::controller(PermissionController::class)->prefix('permission')->group(function () {
            Route::get('index', 'index')->name('permission.index');
            Route::get('form/{id?}', 'form')->name('permission.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('permission.manage');
        });

        // Content Management Routing
        Route::controller(ContentController::class)->prefix('content')->group(function () {
            Route::get('index', 'index')->name('content.index');
            Route::get('form/{id?}', 'form')->name('content.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('content.manage');
            Route::get('view{id?}', 'view')->name('content.view');
        });

        // Email Template Managenent Routing
        Route::controller(EmailTemplateController::class)->prefix('email-template')->group(function () {
            Route::get('', 'index')->name('emailTemplate.index');
            Route::get('form/{id?}', 'form')->name('emailTemplate.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('emailTemplate.manage');
            Route::get('view{id?}', 'view')->name('emailTemplate.view');
            Route::get('status', 'status')->name('emailTemplate.status');
        });

        // Page Managenent Routing
        Route::controller(PageManagerController::class)->prefix('page-manager')->group(function () {
            Route::get('', 'index')->name('pageManager.index');
            Route::get('form/{id?}', 'form')->name('pageManager.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('pageManager.manage');
            Route::get('view{id?}', 'view')->name('pageManager.view');
            Route::get('status', 'status')->name('pageManager.status');
        });

        // User Managenent Routing
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::get('', 'index')->name('users.index'); 
            Route::get('add', 'add')->name('users.add');
            Route::Post('create', 'create')->name('users.create');
            Route::get('edit/{id}', 'edit')->name('users.edit');
            Route::Post('update', 'update')->name('users.update');

             Route::get('states-by-country', 'getStatesByCountry')->name('users.states-by-country');
             Route::get('districts-by-state', 'getDistrictsByState')->name('users.districts-by-state');
             Route::get('tehsil-by-district', 'getTehsilByState')->name('users.tehsil-by-district');
             Route::get('panchayat-by-tehsil', 'getPanchayatBytehsil')->name('users.panchayat-by-tehsil');
             Route::get('village-by-panchayat', 'getVillageBytehsil')->name('users.village-by-panchayat');

             


 

            Route::get('form/{id?}', 'form')->name('users.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('users.manage');
            Route::get('view{id?}', 'view')->name('users.view');
            Route::get('status', 'status')->name('users.status');
            Route::get('delete', 'delete')->name('users.delete');
            Route::get('users_export{id?}', 'users_export')->name('users.users_export');
        });


          // User Managenent Routing
          Route::controller(FamiliesController::class)->prefix('families')->group(function () {
            Route::get('', 'index')->name('families.index'); 
            Route::get('add', 'add')->name('families.add');
            Route::Post('create', 'create')->name('families.create');
            Route::get('edit/{id}', 'edit')->name('families.edit');
            Route::Post('update', 'update')->name('families.update');
            Route::get('delete_feature', 'delete_feature')->name('families.delete_feature');

             Route::get('states-by-country', 'getStatesByCountry')->name('families.states-by-country');
             Route::get('districts-by-state', 'getDistrictsByState')->name('families.districts-by-state');
             Route::get('tehsil-by-district', 'getTehsilByState')->name('families.tehsil-by-district');
             Route::get('panchayat-by-tehsil', 'getPanchayatBytehsil')->name('families.panchayat-by-tehsil');
             Route::get('village-by-panchayat', 'getVillageBytehsil')->name('families.village-by-panchayat');

             Route::get('copy-family', 'getCopyFamily')->name('families.copy-family');
             Route::get('read-notification', 'readNotification')->name('families.read-notification');
 

            Route::get('form/{id?}', 'form')->name('families.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('families.manage');
            Route::get('view{id?}', 'view')->name('families.view');
            Route::get('status', 'status')->name('families.status');
            Route::get('delete', 'delete')->name('families.delete');
            Route::get('users_export{id?}', 'users_export')->name('families.users_export');
        });
 

        // FAQs Managenent Routing
        Route::controller(FaqsController::class)->prefix('faqs-manager')->group(function () {
            Route::get('', 'index')->name('faqs.index');
            Route::get('form/{id?}', 'form')->name('faqs.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('faqs.manage');
            Route::get('view{id?}', 'view')->name('faqs.view');
            Route::get('status', 'status')->name('faqs.status');
            Route::get('delete', 'delete')->name('faqs.delete');
        });

        // Template Category Routing
        Route::controller(TemplateCategoryController::class)->prefix('education')->group(function () {
            Route::get('', 'index')->name('templateCategory.index');
            Route::get('form/{id?}', 'form')->name('templateCategory.form'); 
            Route::Post('manage', 'manage')->name('templateCategory.manage');
            //Route::get('view{id?}', 'view')->name('templateCategory.view');
            Route::get('status', 'status')->name('templateCategory.status');
            Route::get('delete', 'delete')->name('templateCategory.delete');
        });

        // coupon code Routing
        Route::controller(CouponController::class)->prefix('coupon-code')->group(function () {
            Route::get('', 'index')->name('coupon.index');
            Route::get('form/{id?}', 'form')->name('coupon.form'); 
            Route::Post('manage', 'manage')->name('coupon.manage');
            Route::get('view{id?}', 'view')->name('coupon.view');
            Route::get('status', 'status')->name('coupon.status');
            Route::get('delete', 'delete')->name('coupon.delete');
            Route::get('send_coupon{via?}', 'send_coupon')->name('coupon.send_coupon');
            Route::Post('send_via_email', 'send_via_email')->name('coupon.send_via_email');
            Route::Post('send_via_sms', 'send_via_sms')->name('coupon.send_via_sms');
        }); 
        
        // Template Routing
        Route::controller(TemplateController::class)->prefix('templates')->group(function () {
            Route::get('', 'index')->name('template.index');
            Route::get('form/{id?}', 'form')->name('template.form'); 
            Route::Post('manage', 'manage')->name('template.manage');
            Route::get('view{id?}', 'view')->name('template.view');
            Route::get('status', 'status')->name('template.status');
            Route::get('delete', 'delete')->name('template.delete');
        });

        // Testimonial Managenent Routing
        Route::controller(TestimonialController::class)->prefix('occupations')->group(function () {
            Route::get('', 'index')->name('testimonial.index');
            Route::get('form/{id?}', 'form')->name('testimonial.form');  //ajax request route add and edit form 
            Route::Post('manage', 'manage')->name('testimonial.manage');
            Route::get('view{id?}', 'view')->name('testimonial.view');
            Route::get('status', 'status')->name('testimonial.status');
            Route::get('delete', 'delete')->name('testimonial.delete');
        });

        // Payment History Routing
        Route::controller(PaymentsController::class)->prefix('payments')->group(function () {
            Route::get('', 'index')->name('payments.index');
            Route::get('view{id?}', 'view')->name('payments.view');
            Route::get('payment_export{id?}', 'payment_export')->name('payments.payment_export');
            Route::get('get_plans{id?}', 'get_plans')->name('payments.get_plans');
            //Route::get('send_invoice{id?}', 'send_invoice')->name('payments.send_invoice');
            Route::get('download_invoice{id?}', 'download_invoice')->name('payments.download_invoice');
        });

        // Report Managenent Routing
        Route::controller(ReportsController::class)->prefix('reports')->group(function () {
            Route::get('users_report', 'users_report')->name('reports.users_report');
            Route::get('reviews_report', 'reviews_report')->name('reports.reviews_report');
            Route::get('payment_report', 'payment_report')->name('reports.payment_report');
            Route::get('trial_report', 'trial_report')->name('reports.trial_report');
            Route::get('users_export{id?}', 'users_export')->name('reports.users_export');
            Route::get('reviews_export{id?}', 'reviews_export')->name('reports.reviews_export');
            Route::get('payment_export{id?}', 'payment_export')->name('reports.payment_export');
            Route::get('trial_export{id?}', 'trial_export')->name('reports.trial_export');
        });

        // Subscription Managenent Routing
        Route::controller(SubscriptionController::class)->prefix('subscriptions')->group(function () {
            Route::get('', 'index')->name('subscription.index');
            Route::get('form/{id?}', 'form')->name('subscription.form');  //ajax request route add and edit form 
            Route::get('mostpopular/{id?}', 'mostpopular')->name('subscription.mostpopular');  //ajax request route add and edit form 
            Route::Post('managemp', 'managemp')->name('subscription.managemp');
            Route::Post('manage', 'manage')->name('subscription.manage');
            Route::get('view{id?}', 'view')->name('subscription.view');
            Route::get('status', 'status')->name('subscription.status');
            Route::get('delete', 'delete')->name('subscription.delete');
            Route::get('delete_feature', 'delete_feature')->name('subscription.delete_feature');
        });


        // By Pranav raj 
                // Countries Managenent Routing
                Route::controller(CountriesController::class)->prefix('countries')->group(function () {
                    Route::get('', 'index')->name('countries.index');
                    Route::get('form/{id?}', 'form')->name('countries.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('countries.manage');
                    Route::get('view{id?}', 'view')->name('countries.view');
                    Route::get('status', 'status')->name('countries.status');
                    Route::get('delete', 'delete')->name('countries.delete');
                });

         // By Pranav raj 
                // States Managenent Routing
                Route::controller(StatesController::class)->prefix('states')->group(function () {
                    Route::get('', 'index')->name('states.index');
                    Route::get('form/{id?}', 'form')->name('states.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('states.manage');
                    Route::get('view{id?}', 'view')->name('states.view');
                    Route::get('status', 'status')->name('states.status');
                    Route::get('delete', 'delete')->name('states.delete');
                });

        // By Pranav raj 
                // States Managenent Routing
                Route::controller(DistrictController::class)->prefix('districts')->group(function () {
                    Route::get('', 'index')->name('districts.index');
                    Route::get('form/{id?}', 'form')->name('districts.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('districts.manage');
                    Route::get('view{id?}', 'view')->name('districts.view');
                    Route::get('status', 'status')->name('districts.status');
                    Route::get('delete', 'delete')->name('districts.delete');
                    Route::get('states-by-country', 'getStatesByCountry')->name('districts.states-by-country');
                });
          // By Pranav raj 
                // States Managenent Routing
                Route::controller(TehsilsController::class)->prefix('tehsils')->group(function () {
                    Route::get('', 'index')->name('tehsils.index');
                    Route::get('form/{id?}', 'form')->name('tehsils.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('tehsils.manage');
                    Route::get('view{id?}', 'view')->name('tehsils.view');
                    Route::get('status', 'status')->name('tehsils.status');
                    Route::get('delete', 'delete')->name('tehsils.delete');
                    Route::get('states-by-country', 'getStatesByCountry')->name('tehsils.states-by-country');
                    Route::get('districts-by-state', 'getDistrictsByState')->name('tehsils.districts-by-state');
                });

            // By Pranav raj 
                // States Managenent Routing
                Route::controller(PanchayatController::class)->prefix('panchayat')->group(function () {
                    Route::get('', 'index')->name('panchayat.index');
                    Route::get('form/{id?}', 'form')->name('panchayat.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('panchayat.manage');
                    Route::get('view{id?}', 'view')->name('panchayat.view');
                    Route::get('status', 'status')->name('panchayat.status');
                    Route::get('delete', 'delete')->name('panchayat.delete');
                    Route::get('states-by-country', 'getStatesByCountry')->name('panchayat.states-by-country');
                    Route::get('districts-by-state', 'getDistrictsByState')->name('panchayat.districts-by-state');
                    Route::get('tehsil-by-districts', 'gettehsilByDistricts')->name('panchayat.tehsil-by-districts');
                });


            // By Pranav raj 
                // States Managenent Routing
                Route::controller(VillageController::class)->prefix('village')->group(function () {
                    Route::get('', 'index')->name('village.index');
                    Route::get('form/{id?}', 'form')->name('village.form');  //ajax request route add and edit form 
                    Route::Post('manage', 'manage')->name('village.manage');
                    Route::get('view{id?}', 'view')->name('village.view');
                    Route::get('status', 'status')->name('village.status');
                    Route::get('delete', 'delete')->name('village.delete');
                    Route::get('states-by-country', 'getStatesByCountry')->name('village.states-by-country');
                    Route::get('districts-by-state', 'getDistrictsByState')->name('village.districts-by-state');
                    Route::get('tehsil-by-districts', 'gettehsilByDistricts')->name('village.tehsil-by-districts');
                    Route::get('panchayat-by-tehsil', 'getpanchayatByTehsil')->name('village.panchayat-by-tehsil');
                });


    });
});
