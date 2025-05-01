<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FocalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [AdminController::class, 'redirectToLogin']);

Route::controller(PublicController::class)->group(function(){
    Route::get('/freedom-of-information', 'index')->name('public');
    Route::get('/beneficiary-application', 'application')->name('public.application');

    Route::post('/validate-details', 'validateApplicationDetails')->name('public.validateApplicationDetails');
    Route::post('/apply-now', 'apply')->name('public.apply');

});

Route::middleware(['auth', 'user-role:beneficiary'])->prefix('beneficiary')->group(function(){
    Route::controller(PublicController::class)->group(function(){
        Route::get('/my-dashboard', 'dashboard')->name('beneficiary.dashboard');
        Route::get('/my-program', 'program')->name('beneficiary.program');
        Route::get('/my-profile', 'profile')->name('beneficiary.profile');
    });
});

Route::middleware(['auth', 'user-role:focal'])->prefix('focal')->group(function(){
    Route::controller(FocalController::class)->group(function(){
        //GET ROUTES
        Route::get('/dashboard', 'index')->name('focal.dashboard');
        Route::get('/beneficiaries', 'beneficiaries')->name('focal.beneficiaries');
        Route::get('/programs', 'programs')->name('focal.programs');
        Route::get('/application', 'application')->name('focal.application');
        
        //Request Group
        Route::prefix('requests')->group(function(){
            //GET
            Route::get('/beneficiary', 'new_beneficiary')->name('focal.new_beneficiary');
            Route::get('/birthday', 'birthday')->name('focal.birthday');
            Route::get('/transitioning', 'transitioning')->name('focal.transitioning');
            Route::get('/senior-transitioning', 'senior_transitioning')->name('focal.senior_transitioning');
            Route::get('/request_info/{id}', 'request_info')->name('focal.request_info');
            Route::get('/program_info/{id}', 'program_info')->name('focal.program_info');
            //POST
            Route::post('/endorse-transitioning', 'endorseBeneficiary')->name('focal.endorse_transitioning');
            Route::post('/endorse-birthday-transitioning', 'endorseBirthdayBeneficiary')->name('focal.endorse_birthday_transitioning');

            //PUT
            Route::put('/retry-endorse-transitioning/{id}', 'retryEndorseBeneficiary')->name('focal.retry_endorse_transitioning');
            Route::put('/retry-birthday-endorse-transitioning/{id}', 'retryBirthdayEndorseBeneficiary')->name('focal.retry_birthday_endorse_transitioning');
            Route::put('/respond-transition/{id}/{status}', 'respondTransition')->name('focal.put-respond-transition');

            //DELETE
            Route::delete('/cancel-endorse-transitioning/{id}', 'cancelEndorseBeneficiary')->name('focal.cancel_endorse_transitioning');
            Route::delete('/cancel-birthday-endorse-transitioning/{id}', 'cancelBirthdayEndorseBeneficiary')->name('focal.cancel_birthday_endorse_transitioning');
        });

        //END GET ROUTES

        //POST ROUTES
        Route::post('/beneficiaries/create-benefeciary', 'createBenefeciary')->name('focal.create-beneficiary');
        Route::post('/beneficiaries/create-request', 'createRequest')->name('focal.create-request');

        //PUT ROUTES
        Route::put('/beneficiaries/update-benefeciary/{id}', 'UpdateBenefeciary')->name('focal.update-beneficiary');
        Route::put('/beneficiaries/update-request/{id}', 'editRequest')->name('focal.update-request');
        Route::put('/respond-application', 'respondApplication')->name('focal.respondApplication');
        

        Route::delete('/beneficiaries/delete-benefeciary/{id}/{type}', 'deleteBenefeciary')->name('focal.delete-beneficiary');
        Route::delete('/beneficiaries/delete-request/{id}', 'deleteRequest')->name('focal.delete-request');
    });

});

Route::middleware(['auth', 'user-role:barangay'])->prefix('barangay')->group(function(){
    Route::controller(BarangayController::class)->group(function(){

        //GET ROUTES
        Route::get('/dashboard', 'index')->name('barangay.dashboard');
        Route::get('/programs', 'programs')->name('barangay.programs');
        Route::get('/program_info/{id}', 'program_info')->name('barangay.program_info');
        Route::get('/focals', 'focals')->name('barangay.focals');

        //Beneficiaries
        Route::prefix('beneficiaries')->group(function(){
            Route::get('/pwd', 'pwd')->name('barangay.pwd');
            Route::get('/senior', 'senior')->name('barangay.senior');
            Route::get('/women', 'women')->name('barangay.women');
        });

        //Requests
        Route::prefix('requests')->group(function(){
            Route::get('/beneficiary', 'new_beneficiary')->name('barangay.new_beneficiary');
            Route::get('/transitioning', 'transitioning')->name('barangay.transitioning');
        });
        //END GET ROUTES

        //POST ROUTES

        Route::prefix('post')->group(function(){
            Route::post('/focals', 'createUsers')->name('barangay.post-focals');
        });

        //END POST ROUTES

        //PUT ROUTES

        Route::prefix('put')->group(function(){
            Route::put('/focals/{id}', 'updateUsers')->name('barangay.put-focals');
            Route::put('/respond-transition/{id}/{status}', 'respondTransition')->name('barangay.put-respond-transition');
            Route::put('/respond-requests', 'respondRequest')->name('barangay.respondRequest');
            Route::put('/focals/password/{id}', 'updatePassword')->name('barangay.put-focals-password');
        });

        //END PUT ROUTES

        //DELETE ROUTES

        
        Route::prefix('delete')->group(function(){
            Route::put('/focals/{id}', 'deleteUsers')->name('barangay.delete-focals');
        });
        //END DELETE ROUTES
    });

    Route::controller(ReportController::class)->group(function(){

    });
});

Route::middleware(['auth', 'user-role:staff'])->prefix('staff')->group(function(){
    Route::controller(StaffController::class)->group(function(){
        //GET ROUTES
        Route::get('/dashboard', 'index')->name('staff.dashboard');
        Route::get('/programs', 'programs')->name('staff.programs');
        Route::get('/program_info/{id}', 'program_info')->name('staff.program_info');
        Route::get('/aics_form', 'aics_form')->name('staff.aics_form');
        Route::get('/aics_form/{id}', 'aics_edit_form')->name('staff.aics_edit_form');

        //POST ROUTES
        Route::post('/create-program', 'createProgram')->name('staff.create-program');
        Route::post('/add-program-beneficiary', 'addProgramBeneficiary')->name('staff.add-program-beneficiary');
        Route::post('/create-aics-beneficiary', 'createAicsBeneficiary')->name('staff.create-aics-beneficiary');
        Route::post('/add-aics-staff', 'addAicsStaff')->name('staff.add-aics-staff');
            
        //PUT ROUTES
        Route::put('/update-program/{id}', 'updateProgram')->name('staff.update-program');
        Route::put('/submit-program-beneficiary/{id}', 'submitProgramBeneficiary')->name('staff.submit-program-beneficiary');
        Route::put('/cancel-list-submission/{id}', 'cancelSubmission')->name('staff.cancel-list-submission');
        Route::put('/update-aics-beneficiary/{id}', 'updateAicsBeneficiary')->name('staff.update-aics-beneficiary');
        Route::put('/edit-aics-staff', 'editStaffList')->name('staff.edit-aics-staff');
        Route::put('/archive-aics-staff/{id}/{status}', 'archiveAicsStaff')->name('staff.archive-aics-staff');
        
        //DELETE ROUTES
        Route::delete('/delete-program/{id}', 'deleteProgram')->name('staff.delete-program');
        Route::delete('/remove-program/{id}', 'removeProgramBeneficiary')->name('staff.remove-program-beneficiary');
        Route::delete('/delete-aics-beneficiary/{id}', 'deleteAicsBeneficiary')->name('staff.delete-aics-beneficiary');
        
        

        //Route Beneficiaries
        Route::prefix('beneficiaries')->group(function(){
        
            Route::get('/aics', 'aics')->name('staff.aics');
            Route::get('/all-beneficiaries', 'beneficiary_report')->name('staff.all-beneficiaries');
            

        });

         //Route Requests
        Route::prefix('requests')->group(function(){
            
            //GET
            Route::get('/new-beneficiaries', 'new_beneficiaries')->name('staff.new_beneficiaries');
            Route::put('/respond-request_info', 'respondRequest')->name('staff.respondRequest');
        });

    });

    Route::controller(ReportController::class)->group(function(){

        Route::get('/beneficiary-report-excel-pwd/{barangay}', 'exportToExcelPwd')->name('staff.exportToExcelPwd');
        Route::get('/beneficiary-report-excel-women/{barangay}', 'exportToExcelWomen')->name('staff.exportToExcelWomen');
        Route::get('/beneficiary-report-excel-senior/{barangay}', 'exportToExcelSenior')->name('staff.exportToExcelSenior');
        Route::get('/beneficiary-report-excel-aics/{data}', 'exportToExcelAics')->name('staff.exportToExcelAics');
        Route::get('/beneficiary-report-pdf-aics/{data}', 'exportToPdfAics')->name('staff.exportToPdfAics');

        Route::get('/program-report-pdf/{from}/{to}', 'programReport')->name('staff.programReport');
        Route::get('/individual-program-report-pdf/{id}', 'individualProgramReport')->name('staff.individualProgramReport');
        Route::get('/program-report-excel/{from}/{to}', 'exportToExcelProgram')->name('staff.exportToExcelProgram');

        //API
        Route::get('/get-programs', 'findPrograms')->name('staff.findPrograms');
        Route::get('/get-beneficiaries', 'findBeneficiaries')->name('staff.findBeneficiaries');
        Route::get('/get-aics-beneficiaries', 'findAicsBeneficiaries')->name('staff.findAicsBeneficiaries');
    });
});

Route::middleware(['auth', 'user-role:admin'])->prefix('admin')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        //GET Routes
        Route::get('/dashboard', 'index')->name('admin.dashboard');
        Route::get('/users', 'users')->name('admin.users');

        //Request
        Route::prefix('request')->group(function(){
            //GET
            Route::get('/programs', 'program')->name('admin.request.programs');
            Route::get('/beneficiaries', 'new_beneficiary')->name('admin.request.beneficiaries');
            Route::get('/request_info/{id}', 'request_info')->name('admin.request_info');
            Route::get('/birthdays', 'birthday')->name('admin.request.birthdays');
            Route::get('/aics_form', 'aics_form')->name('admin.aics_form');
            Route::get('/aics_form/{id}', 'aics_edit_form')->name('admin.aics_edit_form');
            Route::get('/program_info/{id}', 'program_info')->name('admin.program_info');
            //POST

            //PUT
            
            Route::put('/respond-request_info', 'respondRequest')->name('admin.respondRequest');
            Route::put('/respond-birthday-request', 'respondBirthdayRequest')->name('admin.respondBirthdayRequest');
            Route::put('/review-program', 'respondProgramRequest')->name('admin.respondProgramRequest');
            Route::put('/approve-program-list/{id}', 'approveProgram')->name('admin.approve-program-list');
            Route::put('/accept-aics-beneficiary', 'accept_aics')->name('admin.accept-aics-beneficiary');
            Route::put('/archive-beneficiary/{id}/{status}', 'archiveBeneficiary')->name('admin.archive-beneficiary');
        });

        //Beneficiaries
        Route::prefix('beneficiary')->group(function(){
            Route::get('/beneficiaries', 'beneficiary_report')->name('admin.report.beneficiaries');
            Route::get('/aics', 'beneficiary_aics')->name('admin.beneficiaries.aics');
            Route::get('/mapping', 'beneficiary_mapping')->name('admin.beneficiaries.mapping');
            Route::get('/beneficiary_barangay/{barangay_id}', 'beneficiary_barangay')->name('admin.beneficiary_barangay');
            
            
        });
        

        //End GET routes

        //Benefeciaries
        Route::get('/beneficiaries', 'benefeciaries')->name('admin.benefeciaries');
        
        Route::post('/users/create-user', 'createUsers')->name('admin.create-user');
        Route::put('/users/update-user/{id}', 'updateUsers')->name('admin.update-user');
        Route::put('/users/update-password/{id}', 'updatePassword')->name('admin.update-password');
        Route::delete('/users/delete-user/{id}', 'deleteUsers')->name('admin.delete-user');

        Route::get('/requests', 'index')->name('admin.request');
        Route::get('/barangay', 'barangays')->name('admin.barangay');


        

    });


    Route::controller(ReportController::class)->group(function(){
        Route::get('/beneficiary-report-excel-pwd/{barangay}', 'exportToExcelPwd')->name('admin.exportToExcelPwd');
        Route::get('/beneficiary-report-excel-women/{barangay}', 'exportToExcelWomen')->name('admin.exportToExcelWomen');
        Route::get('/beneficiary-report-excel-senior/{barangay}', 'exportToExcelSenior')->name('admin.exportToExcelSenior');
        Route::get('/beneficiary-report-excel-aics/{data}', 'exportToExcelAics')->name('admin.exportToExcelAics');
        Route::get('/beneficiary-report-pdf-aics/{data}', 'exportToPdfAics')->name('admin.exportToPdfAics');

        Route::get('/program-report-pdf/{from}/{to}', 'programReport')->name('admin.programReport');
        Route::get('/individual-program-report-pdf/{id}', 'individualProgramReport')->name('admin.individualProgramReport');
        Route::get('/program-report-excel/{from}/{to}', 'exportToExcelProgram')->name('admin.exportToExcelProgram');

        //API
        Route::get('/get-programs', 'findPrograms')->name('admin.findPrograms');
        Route::get('/get-beneficiaries', 'findBeneficiaries')->name('admin.findBeneficiaries');
        Route::get('/get-aics-beneficiaries', 'findAicsBeneficiaries')->name('admin.findAicsBeneficiaries');
    });
});

Route::middleware(['auth'])->group(function(){
    Route::controller(NotificationController::class)->group(function(){
        Route::post('/markread-notification/{id}', 'markasReadNotification')->name('markasReadNotification');
    });
});




require __DIR__.'/auth.php';
