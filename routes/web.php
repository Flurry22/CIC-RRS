<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    AddResearcherController,
    ViewResearchersController,
    ResearcherDashboardController,
    ResearcherSettingsController,
    AddResearchController,
    ShowResearchController,
    ResearcherSearchController,
    ResearcherProfileController,
    AcademicAdminController,
    AdminManageResearchController,
    AdminManageResearcherController,
    SchoolYearController,
    ResearcherRepositoryController,
    ResearchStaffDashboardController,
    ResearchFilesController,
    ResearcherResearchFilesController,
    ResearchReportController,

};

// ==========================================
// Authentication Routes
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// Academic Administrator Routes
// ==========================================
Route::middleware(['auth:academic_administrator'])->group(function () {
    // Dashboard Route
    Route::get('/academic_administrator/dashboard', [AcademicAdminController::class, 'dashboard'])->name('academic_administrator.dashboard');
    Route::post('/admin/update-credentials', [AcademicAdminController::class, 'updateCredentials'])->name('admin.updateCredentials');
    // Manage Research Routes
    Route::get('/manage-research', [AdminManageResearchController::class, 'index'])->name('academic_administrator.manage_research');
        Route::get('/academic_administrator/manage_research/{researchId}/edit-leader', [AdminManageResearchController::class, 'editLeader'])
        ->name('academic_administrator.edit_leader');
        Route::put('/update-leader/{researchId}', [AdminManageResearchController::class, 'updateLeader'])
        ->name('academic_administrator.update_leader');

    // Manage Researchers Routes
    Route::get('/academic_administrator/researchers', [AdminManageResearcherController::class, 'index'])
        ->name('manage.researchers');
// School Year
Route::get('/school-year/create', [SchoolYearController::class, 'create'])->name('school_years.create');
Route::get('/school-years', [SchoolYearController::class, 'viewUpdateschoolyear'])->name('school_years.viewUpdateschoolyear');
Route::post('/school-year', [SchoolYearController::class, 'store'])->name('school_years.store');
Route::get('/school-year/{schoolYear}/edit', [SchoolYearController::class, 'edit'])->name('school_years.edit');
Route::put('/school-year/{schoolYear}', [SchoolYearController::class, 'update'])->name('school_years.update');
Route::delete('/school-year/{schoolYear}', [SchoolYearController::class, 'destroy'])->name('school_years.destroy');

Route::get('/admin/staff/create', [AcademicAdminController::class, 'create'])->name('staff.create');
    Route::post('/admin/staff/store', [AcademicAdminController::class, 'store'])->name('staff.store');

});

// ==========================================
// Research Staff Routes
// ==========================================
Route::middleware('auth:research_staff')->group(function () {
    // Dashboard Route
    Route::get('/research_staff/dashboard', [ResearchStaffDashboardController::class, 'index'])
        ->name('research_staff.dashboard');
        Route::post('/update-credentials', [ResearchStaffDashboardController::class, 'updateCredentials'])->name('staff.updateCredentials');
    

    // Researcher Management
    Route::prefix('researchers')->group(function () {
        Route::get('/create', [AddResearcherController::class, 'create'])->name('researchers.create');
        Route::post('/', [AddResearcherController::class, 'store'])->name('researchers.store');
        Route::get('/', [ViewResearchersController::class, 'index'])->name('researchers.index');
        Route::get('/{id}/edit', [ViewResearchersController::class, 'edit'])->name('researchers.edit');
        Route::post('/{id}', [ViewResearchersController::class, 'update'])->name('researchers.update');
        Route::delete('/{id}', [ViewResearchersController::class, 'destroy'])->name('researchers.destroy');



        Route::get('/researcher/{id}/repository', [ResearcherRepositoryController::class, 'show'])->name('researcher.repository');

// Update these routes to handle file viewing instead of downloading
Route::get('research/{id}/view-certificate', [ResearcherRepositoryController::class, 'viewCertificate'])->name('research.viewCertificate');
Route::get('research/{id}/view-special-order', [ResearcherRepositoryController::class, 'viewSpecialOrder'])->name('research.viewSpecialOrder');
Route::get('research/{id}/view-approved-file', [ResearcherRepositoryController::class, 'viewApprovedProposalFile'])->name('research.viewApprovedProposalFile');
Route::get('research/{id}/view-terminal-file', [ResearcherRepositoryController::class, 'viewTerminalFile'])->name('research.viewTerminalFile');
Route::get('research/{id}/view-proposal-file', [ResearcherRepositoryController::class, 'viewProposalFile'])->name('research.viewProposalFile');
       
    });

       // Research Management
       Route::prefix('research')->group(function () {
        Route::get('/create/{type}', [AddResearchController::class, 'create'])->name('research.create');
        Route::post('/store/{type}', [AddResearchController::class, 'store'])->name('research.store');
        Route::get('/', [AddResearchController::class, 'index'])->name('research.index');
        Route::delete('/{id}', [AddResearchController::class, 'destroy'])->name('research.destroy');
       
    });
  

    // Show and Update Research
    Route::prefix('research/{id}')->group(function () {
        Route::get('/', [ShowResearchController::class, 'show'])->name('research.show');
        Route::patch('/update-status', [ShowResearchController::class, 'updateStatus'])->name('research.update_status');
        Route::patch('/update-certificate', [ShowResearchController::class, 'updateCertificate'])->name('research.update_certificate');
        Route::patch('/update-special-order', [ShowResearchController::class, 'updateSpecialOrder'])->name('research.update_special_order');
        Route::get('/download-certificate', [ShowResearchController::class, 'downloadCertificate'])->name('research.download_certificate');
        Route::get('/download-special-order', [ShowResearchController::class, 'downloadSpecialOrder'])->name('research.download_special_order');
        Route::patch('/update-approved-date', [ShowResearchController::class, 'updateApprovedDate'])->name('research.update_approved_date');
        Route::patch('/update-approved-file', [ShowResearchController::class, 'updateApprovedFile'])->name('research.update_approved_file');
        Route::patch('/update-terminal-file', [ShowResearchController::class, 'updateTerminalFile'])->name('research.update_terminal_file');
        Route::get('/download-approved-file', [ShowResearchController::class, 'downloadApprovedFile'])->name('research.download_approved_file');
        Route::get('/download-terminal-file', [ShowResearchController::class, 'downloadTerminalFile'])->name('research.download_terminal_file');
        Route::get('/download-proposal-file', [ShowResearchController::class, 'downloadProposalFile'])->name('research.download_proposal_file');
        Route::patch('/update-proposal-file', [ShowResearchController::class, 'updateProposalFile'])->name('research.update_proposal_file');
        Route::get('/view-proposal-file', [ShowResearchController::class, 'viewApprovedProposalFile'])->name('research.viewApprovedProposalFile');
        Route::get('/view-certificate', [ShowResearchController::class, 'viewCertificate'])->name('research.viewCertificate');
        Route::get('/view-special-order', [ShowResearchController::class, 'viewSpecialOrder'])->name('research.viewSpecialOrder');
        Route::get('/view-approved-file', [ShowResearchController::class, 'viewApprovedFile'])->name('research.viewApprovedFile');
        Route::get('/view-terminal-file', [ShowResearchController::class, 'viewTerminalFile'])->name('research.viewTerminalFile');
        Route::put('/update-programs', [ShowResearchController::class, 'updatePrograms'])->name('research.updatePrograms');
        Route::patch('/update-title', [ShowResearchController::class, 'updateTitle'])->name('research.updateTitle');
        Route::patch('/update-deadline', [ShowResearchController::class, 'updateDeadline'])->name('research.updateDeadline');
        Route::post('/update-duration', [ShowResearchController::class, 'updateProjectDuration'])->name('research.updateDuration');
        Route::post('/download-zip', [ShowResearchController::class, 'downloadSelectedFiles'])->name('research.download.zip');

    });
   
    Route::get('/research-files', [ResearchFilesController::class, 'index'])->name('research-files.index');
    Route::post('/research-files/upload', [ResearchFilesController::class, 'upload'])->name('research-files.upload');
        Route::get('/research-files/download/{id}', [ResearchFilesController::class, 'download'])->name('research-files.download');
        Route::delete('/delete/{id}', [ResearchFilesController::class, 'delete'])->name('delete');
        Route::delete('/research-files/delete/{id}', [ResearchFilesController::class, 'delete'])->name('research-files.delete');
    
        // Show research report creation page
        Route::get('research-report/create', [ResearchReportController::class, 'create'])->name('research-report.create');
        Route::get('research-report/preview', [ResearchReportController::class, 'preview'])->name('research-report.preview');
        Route::post('research-report/generate-pdf', [ResearchReportController::class, 'generatePdf'])->name('research-report.generate-pdf');

    });


// ==========================================
// Researcher Routes
// ==========================================
Route::middleware('auth:researcher')->group(function () {
    // Dashboard
    Route::get('/researcher/dashboard/{id}', [ResearcherDashboardController::class, 'show'])->name('researcher.dashboard');

    // Settings
    Route::prefix('researcher/settings')->group(function () {
        Route::get('/', [ResearcherSettingsController::class, 'edit'])->name('researcher.settings.edit');
        Route::put('/', [ResearcherSettingsController::class, 'update'])->name('researcher.settings.update');
        Route::post('/update-profile-picture', [ResearcherSettingsController::class, 'updateProfilePicture'])->name('researcher.updateProfilePicture');
        Route::post('/change-password', [ResearcherSettingsController::class, 'changePassword'])->name('researcher.changePassword');
    });

    Route::get('/researchers/search', [ResearcherSearchController::class, 'index'])->name('researchers.search');
   
    Route::get('/researchers/{id}', [ResearcherProfileController::class, 'show'])->name('researcher.profile');
    Route::get('/researcher/files', [ResearcherResearchFilesController::class, 'index'])->name('researcher.files.index');
    Route::get('/researcher/files/{id}/download', [ResearcherResearchFilesController::class, 'download'])->name('researcher.files.download');


    
});
