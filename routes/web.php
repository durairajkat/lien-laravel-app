<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LienController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RemedyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\LienDocumentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VineTransferController;
use App\Http\Controllers\ProjectManagementController;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/run-queue', function () {
    Artisan::call('queue:work --once');
    return response()->json(['message' => 'Queue processed successfully.']);
});

Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('/about', [UserController::class, 'about'])->name('about');
Route::post('/contact', [ContactController::class, 'postContact'])->name('member.contact.post');
//Admin Login Routes
Route::get('admin/login', [UserController::class, 'adminLogin'])->name('admin.login');
Route::post('login/submit/admin', [UserController::class, 'postLoginAdmin'])->name('post.login');

//Member Signup Routes
Route::get('/member/basic', [UserController::class, 'getBasicSignup'])->name('member.basicSignup');
Route::get('/member/pro', [UserController::class, 'getProSignup'])->name('member.proSignup');
Route::get('plans/{plan}', [PlanController::class, 'show'])->name('plans.show');
Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
Route::post('subscribe', [SubscriptionController::class, 'create'])->name('subscription.create');


//Member Login routes
Route::get('/login', [UserController::class, 'getLogin'])->name('member.login');
Route::post('login/submit/member', [UserController::class, 'postLoginMember'])->name('member.login.action');
Route::post('signup/submit/member', [UserController::class, 'postNewMember'])->name('member.signup.action');

//Forget Password
Route::get('forget/password', [UserController::class, 'getForgetPassword'])->name('get.forgetPassword');
Route::post('forget/password/submit', [UserController::class, 'postForgetPassword'])->name('post.forgetPassword');
Route::get('password/reset/{token}', [UserController::class, 'getPasswordReset'])->name('password.reset');
Route::post('password/reset-action', [UserController::class, 'postPasswordReset'])->name('post.password.reset');

//Member Registration Route
Route::get('registration/{email}/{pass}', [UserController::class, 'getRegistration'])->name('get.register');
Route::post('registration/submit', [UserController::class, 'postRegistration'])->name('post.register');

//Route for Privacy Policy
Route::get('privacy', [AppController::class, 'privacy'])->name('privacy');

//Route for Terms of Use
Route::get('terms_of_use', [AppController::class, 'terms'])->name('terms');
Route::post('end/subscription', [WebhookController::class, 'handleUpcomingInvoice'])->name('handle.upcoming.invoice');

//Authentication Admin Route Groups
Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    //App Index
    Route::get('/', [AppController::class, 'index'])->name('admin.dashboard');

    //Logout
    Route::get('logout', [UserController::class, 'logout'])->name('admin.logout');

    //Admin profile route
    Route::get('profile', [AppController::class, 'adminProfile'])->name('admin.profile');
    Route::post('profile/update', [AppController::class, 'adminProfileUpdate'])->name('admin.profile.update');
    Route::get('autocomplete/jobinfo', [LienController::class, 'autoCompleteJobInfo'])->name('autocomplete.jobinfo');

    //User management Routes
    Route::get('user/agency', [MemberController::class, 'agencyUser'])->name('user.agency');
    Route::get('user/business', [MemberController::class, 'businessUser'])->name('user.business');
    Route::get('user/sub', [MemberController::class, 'subUser'])->name('user.sub');
    Route::get('user/member', [MemberController::class, 'memberUser'])->name('user.member');
    Route::get('user/member/edit/{id}', [MemberController::class, 'updateMemberProfile'])->name('user.member.edit');
    Route::get('user/member/editmodal', [MemberController::class, 'updateMemberProfileModal'])->name('user.member.edit.modal');
    Route::get('user/sub-user/edit/{id}', [MemberController::class, 'updateSubUserProfile'])->name('user.sub.edit');
    Route::post('user/member/edit', [MemberController::class, 'updateProfileAction'])->name('user.member.edit.action');
    Route::post('user/member/ModelUpdate', [MemberController::class, 'updateProfileModalAction'])->name('user.member.edit.model.action');
    Route::get('user/sub-user/company', [MemberController::class, 'getCompanyDetails'])->name('admin.subuser.companydetails');
    Route::post('user/sub-user/edit', [MemberController::class, 'updateSubUserProfileAction'])->name('user.sub.edit.action');
    Route::post('user/member/delete', [MemberController::class, 'deleteMember'])->name('member.delete');
    Route::post('user/add/agency', [MemberController::class, 'addAgency'])->name('user.add.agency');
    Route::post('user/add/member', [MemberController::class, 'addMember'])->name('user.add.member');
    Route::post('user/add/sub-user', [MemberController::class, 'addSubUser'])->name('user.add.sub');
    Route::post('user/change/status', [MemberController::class, 'userStatus'])->name('user.status');

    //State Routes
    Route::get('state', [StateController::class, 'index'])->name('app.state');
    Route::post('state/add', [StateController::class, 'addState'])->name('add.state');
    Route::post('state/delete', [StateController::class, 'deleteState'])->name('delete.state');
    Route::post('state/edit', [StateController::class, 'editState'])->name('edit.state');

    //Project management Routes
    Route::get('project/management', [ProjectManagementController::class, 'getProjectManagement'])->name('project.management');
    Route::post('project/change/status', [ProjectManagementController::class, 'projectStatus'])->name('project.status');
    Route::post('project/management/action', [ProjectManagementController::class, 'actionProjectManagement'])->name('project.management.action');
    Route::post('project/management/delete', [ProjectManagementController::class, 'deleteProjectManagement'])->name('management.delete');
    Route::post('project/management/tier', [ProjectManagementController::class, 'tierAction'])->name('add.project.tier');
    Route::get('project/management/customer', [RemedyController::class, 'getCustomer'])->name('remedy.get.customer');
    Route::get('project/management/remedy', [RemedyController::class, 'getRemedy'])->name('remedy.get.remedy');
    Route::get('project/management/remedy-dates', [RemedyController::class, 'getRemedyDates'])->name('remedy.get.remedy_dates');
    Route::get('project/management/remedy-dates-private', [RemedyController::class, 'getRemedyDatesPrivate'])->name('remedy.get.remedy_dates_private');
    Route::post('project/management/remedy-dates/private', [RemedyController::class, 'hideRemedyDates'])->name('remedy.hide.remedy_dates');
    Route::post('project/management/remedy-dates/public', [RemedyController::class, 'publicRemedyDates'])->name('remedy.hide.remedy_dates_private');
    Route::get('project/management/remedy-question', [RemedyController::class, 'getRemedyQuestions'])->name('remedy.get.remedy_questions');
    Route::get('project/management/remedy-step', [RemedyController::class, 'getRemedySteps'])->name('remedy.get.remedy_step');
    Route::post('project/management/remedy-steps/private', [RemedyController::class, 'hideRemedySteps'])->name('remedy.hide.remedy_steps');
    Route::post('project/management/remedy-steps/public', [RemedyController::class, 'publicRemedySteps'])->name('remedy.hide.remedy_steps_private');
    Route::get('project/management/tier-remedy-step', [RemedyController::class, 'getTierRemedySteps'])->name('remedy.get.tier_remedy_step');

    //Job request Routes
    Route::get('project/job/request/list', [ProjectManagementController::class, 'jobRequestList'])->name('job.request.list');
    Route::get('project/job/request', [ProjectManagementController::class, 'getJobRequestForm'])->name('project.job.request.form');
    Route::post('project/job/request/action', [ProjectManagementController::class, 'postJobRequestForm'])->name('job.request.action');
    Route::get('project/job/request/edit/{id}', [ProjectManagementController::class, 'getEditJobRequestForm'])->name('job.request.edit');
    Route::post('project/job/request/edit/action', [ProjectManagementController::class, 'actionEditJobRequestForm'])->name('job.request.edit.action');
    Route::post('project/job/request/delete', [ProjectManagementController::class, 'deleteJobRequest'])->name('job.request.delete');
    Route::post('project/job/request/clone', [ProjectManagementController::class, 'cloneJobRequest'])->name('job.request.clone');
    Route::post('project/job/request/check', [ProjectManagementController::class, 'checkJobRequest'])->name('job.request.check.combination');
    Route::post('change/viewable', [LienController::class, 'changeViewable'])->name('admin.change.viewable');

    //import Excel sheet for Tiers and Project Management
    Route::post('project/upload/excel', [ExportController::class, 'uploadExcel'])->name('project.upload.excel');

    //Contact Request
    Route::get('contact/request', [ContactController::class, 'contactRequest'])->name('admin.contact.view');
    Route::get('contact/request/reply/{user_id}', [ContactController::class, 'contactRequestReply'])->name('reply.contact.us');
    Route::post('contact/reply/send', [ContactController::class, 'contactRequestReplySend'])->name('contact.reply.send');
    Route::post('contact/request/delete', [ContactController::class, 'contactRequestDelete'])->name('contact.us.delete');

    //Consultation
    Route::get('consultation', [ConsultationController::class, 'getAdminConsultation'])->name('admin.consultation.view');
    Route::get('consultation/reply/{consultation_id}', [ConsultationController::class, 'replyConsultation'])->name('reply.consultation');
    Route::post('consultation/send/reply', [ConsultationController::class, 'sendReplyConsultation'])->name('consultation.reply.send');
    Route::post('consultation/delete', [ConsultationController::class, 'deleteConsultation'])->name('consultation.delete');

    //Collect Receivable
    Route::get('collect-receivable', [ConsultationController::class, 'viewCollectReceivable'])->name('admin.collectReceivable.view');

    //Projects
    Route::get('view/projects', [ProjectManagementController::class, 'viewProjects'])->name('admin.project.view');
    Route::get('project/show/details/{project_id}', [ProjectManagementController::class, 'projectShowDetails'])->name('admin.project.details');
    Route::get('import/old-data', [ExportController::class, 'oldImport'])->name('import.old.nlb');
    Route::get('export/old-data', [ExportController::class, 'oldExport'])->name('export.old.nlb');
    Route::post('view/projects/delete', [ProjectManagementController::class, 'deleteProjects'])->name('admin.project.delete');
    Route::get('claim_data', [ClaimController::class, 'showClaimAdmin'])->name('admin.claim.view');
    Route::post('claim/delete', [ClaimController::class, 'deleteClaim'])->name('Claim.delete');
    Route::get('cliam/show/details/{claim_id}', [ClaimController::class, 'viewClaim'])->name('admin.claim.details');
    Route::get('cliam/show/details/download/{name}', [ClaimController::class, 'getDownload'])->name('admin.claim.download');
    Route::get('lien-providers', [LienController::class, 'getLien'])->name('lien.list');
    Route::post('lien-provider/create', [LienController::class, 'submitLienProvider'])->name('add.lien.provider');
    Route::post('lien-provider/delete', [LienController::class, 'deleteLien'])->name('lien.delete');
    Route::get('view/lien-law-slide-chart', [LienController::class, 'viewLienLawSlideChart'])->name('lien.law.slide.chart');
    Route::post('add/lien-law-slide-chart', [LienController::class, 'addLienLawSlideChart'])->name('add.lien');
    Route::get('view/job-info', [LienController::class, 'viewJobInfo'])->name('admin.job.info');
    Route::get('autocomplete/admin/company', [MemberController::class, 'autocompleteCompany'])->name('admin.company.autocomplete');
    Route::post('package/state/autopopulate', [MemberController::class, 'packagestateAutopopulate'])->name('package.state.autopopulate');
    Route::post('stripe/webhook', [\App\Http\Controllers\WebhookController::class, 'handleWebhook']);
    Route::post('cancel/subscription', [MemberController::class, 'cancelSubscription'])->name('cancel.subscription');
    Route::get('plans', [MemberController::class, 'getPlans'])->name('admin.member.plans');
    Route::post('plans/update', [MemberController::class, 'updatePlan'])->name('update.member.plan');
    Route::post('get/max-price', [UserController::class, 'getMaxPrice'])->name('package.get.maxPrice');
    Route::get('auto-complete/company', [ContactController::class, 'autoCompleteAdminCompany'])->name('autocomplete.admin.company');
});

//Authentication Member Route Groups
Route::group(['prefix' => 'member', 'middleware' => ['member', 'web']], function () {
    //Member home Page
    Route::get('/', [UserController::class, 'member'])->name('member.dashboard');
    Route::post('/', [UserController::class, 'userCoumn'])->name('member.userCoumn');
    Route::post('user/columnAdd', [UserController::class, 'columnAdd'])->name('user.columnAdd');
    Route::post('update/status', [ProjectManagementController::class, 'projectStatusUpdate'])->name('project.status.update');

    //Member update profile
    Route::get('update/profile', [UserController::class, 'updateMemberProfile'])->name('member.update.profile');
    Route::post('update/profile/action', [UserController::class, 'updateProfileAction'])->name('member.action.profile');
    Route::post('update/notification/action', [UserController::class, 'updateNotificationSettingsAction'])->name('member.action.notification');

    //Member change password
    Route::post('change/password', [UserController::class, 'changePassword'])->name('member.change.password');

    //Member Contact Section
    Route::post('subuser/submit', [UserController::class, 'submitSubuser'])->name('member.subuser.submit');
    Route::post('subuser/create', [UserController::class, 'createSubuser'])->name('member.subuser.create');
    Route::post('subuser/delete', [UserController::class, 'deleteSubuser'])->name('member.subuser.delete');
    Route::get('customer/users', [ContactController::class, 'getUserContacts'])->name('member.contacts.users');
    Route::get('customer/contacts', [ContactController::class, 'getCustomerContacts'])->name('member.contacts.contacts');
    Route::get('industry/contacts', [ContactController::class, 'getIndustryContacts'])->name('member.contacts.industry');

    //Not used
    Route::post('contacts/submit', [ContactController::class, 'submitContact'])->name('customer.submit.contact');
    Route::post('contacts/create', [ContactController::class, 'createContact'])->name('create.contact');
    Route::post('create/contacts', [ContactController::class, 'createNewContact'])->name('create.new.contacts');
    Route::post('contacts/delete', [ContactController::class, 'deleteContacts'])->name('customer.delete.contacts');
    Route::post('contact/delete', [ContactController::class, 'deleteContact'])->name('customer.delete.contact');

    //Member Document Section
    Route::get('document', [DocumentController::class, 'projectDocument'])->name('member.document');
    Route::post('document/save', [DocumentController::class, 'saveProjectDocument'])->name('member.document.save');

    //Member Project Section
    Route::get('project', [ProjectController::class, 'getProject'])->name('member.project');
    Route::get('record/search', [ProjectController::class, 'searchProjectRecord'])->name('member.record.search');
    Route::get('search/construction/monitor', [ProjectController::class, 'searchProjectConstructionMonitor'])->name('member.search.construction.monitor');
    Route::post('create/construction/monitor/project', [ProjectController::class, 'createProjectConstructionMonitor'])->name('member.create.construction.monitor');
    Route::get('project/dashboard', [VineTransferController::class, 'viewDash'])->name('vine.job.view');
    Route::get('project/delete/{id}/{project_id}', [VineTransferController::class, 'delete'])->name('project.Delete');
    Route::post('project/save', [VineTransferController::class, 'save'])->name('project.save');
    Route::post('project/jobcontract', [VineTransferController::class, 'jobcontract'])->name('project.jobcontract');
    Route::post('project/media', [VineTransferController::class, 'storeMedia'])->name('project.storeMedia');
    Route::get('project/json', [VineTransferController::class, 'getJSON'])->name('vine.job.json');
    Route::get('project/send-claim', [VineTransferController::class, 'sendClaim'])->name('vine.job.claim');
    Route::post('project/send-claim', [VineTransferController::class, 'sendClaim'])->name('vine.job.claim');
    Route::get('create/projectcontacts/{project_id}', [ProjectController::class, 'projectContacts'])->name('member.create.projectcontacts');
    Route::get('create/edit/jobdescription/{project_id}', [ProjectController::class, 'reviewJobDescription'])->name('member.create.edit.jobdescription');
    Route::post('create/jobinfo/blank', [ProjectController::class, 'blankJobInfo'])->name('member.create.jobinfo.blank');
    Route::get('create/project', [ProjectController::class, 'createProject'])->name('member.create.project');
    Route::get('create/express/project', [ProjectController::class, 'createExpressProject'])->name('member.create.express.project');
    Route::get('express/dashboard', [ProjectController::class, 'expressDashboard'])->name('member.express.create.project');
    Route::get('project/job-documents-sheet/{project_id}', [DocumentController::class, 'getJobDocuments'])->name('get.job.documents.sheet');
    Route::get('project/project-documents/{project_id}', [DocumentController::class, 'getProjectDocuments'])->name('get.project.documents');
    Route::get('view/project', [ProjectController::class, 'viewProject'])->name('member.view.project');
    Route::get('view/project/mobile/{project_id}', [ProjectController::class, 'viewProjectMobile'])->name('member.view.projectMobile');
    Route::post('project/role/check', [ProjectController::class, 'checkProjectRole'])->name('project.role.check');
    Route::post('project/check/name', [ProjectController::class, 'checkProjectName'])->name('project.name.check');
    Route::post('project/check/question', [ProjectController::class, 'projectCheckQuestion'])->name('project.check.question');
    Route::post('project/save/session', [ProjectController::class, 'projectSaveSession'])->name('project.save.session');
    Route::post('project/get/date', [ProjectController::class, 'getProjectDate'])->name('project.get.date');
    Route::post('project/details/submit', [ProjectController::class, 'submitProjectDetails'])->name('project.details.submit');
    Route::post('project/contact/submit', [ProjectController::class, 'submitProjectContacts'])->name('project.contact.submit');

    Route::get('create/project/remedydates/{project_id}', [ProjectController::class, 'createRemedyDates'])->name('create.remedydates');
    Route::get('create/project/deadlines/{project_id}', [ProjectController::class, 'createDeadlines'])->name('create.deadlines');
    Route::post('project/contract/submit', [ProjectController::class, 'submitProjectContract'])->name('project.contract.submit');
    Route::post('project/contract/update', [ProjectController::class, 'updateProjectContract'])->name('project.contract.update');
    Route::post('project/dates/submit', [ProjectController::class, 'submitProjectDates'])->name('project.dates.submit');
    Route::post('project/dates/update', [ProjectController::class, 'updateProjectDates'])->name('project.dates.update');
    Route::get('project/contract/view', [ProjectController::class, 'viewProjectContract'])->name('project.contract.view');
    Route::get('project/date/view', [ProjectController::class, 'viewProjectDate'])->name('project.date.view');
    Route::get('project/deadline/view', [ProjectController::class, 'viewDeadline'])->name('project.deadline.view');
    Route::get('project/document/view', [ProjectController::class, 'viewProjectDocument'])->name('project.document.view');
    Route::get('project/task/view/{project_id?}', [ProjectController::class, 'viewProjectTask'])->name('project.task.view');
    Route::get('project/lien/view', [ProjectController::class, 'viewProjectLien'])->name('project.lien.view');
    Route::post('project/add/task', [ProjectController::class, 'addProjectTask'])->name('project.add.task');
    Route::post('project/delete', [ProjectController::class, 'deleteProject'])->name('project.delete');
    Route::post('project/task/delete', [ProjectController::class, 'deleteTask'])->name('project.delete.task');
    Route::post('project/task/update', [ProjectController::class, 'updateTask'])->name('project.update.task');
    Route::get('project/deadline/{project_id}', [ProjectController::class, 'projectDeadline'])->name('project.deadline');
    Route::post('project/add/emails/{project_id}', [ProjectController::class, 'addProjectEmails'])->name('project.add.emails');
    Route::get('project/summary/view/{project_id}', [ProjectController::class, 'viewProjectSummary'])->name('project.summary.view');

    //deadline.email.check
    Route::post('project/alert/check', [ProjectController::class, 'getAlertChange'])->name('deadline.email.check');

    //Project Documents
    Route::get('project/line-bound-summary/{state}/{projectType}', [DocumentController::class, 'getLineBoundSummery'])->name('get.lineBoundSummery');
    Route::get('project/document-claim-data/{project_id}', [DocumentController::class, 'getDocumentClaimData'])->name('get.documentClaimData');
    Route::get('project/document-claim-2/{project_id}/{flag}', [DocumentController::class, 'getDocumentClaimData2'])->name('get.documentClaimData2');
    Route::get('project/document-claim-view/{project_id}/{flag}', [DocumentController::class, 'getDocumentClaimView'])->name('get.documentClaimView');
    Route::get('project/document-credit-application-view/{project_id}/{flag}', [DocumentController::class, 'getDocumentCreditView'])->name('get.documentCreditView');
    Route::get('project/document-joint-payment-view/{project_id}/{flag}', [DocumentController::class, 'getDocumentJointView'])->name('get.documentJointView');
    Route::get('project/document-waver-view/{project_id}/{flag}', [DocumentController::class, 'getDocumentWaverView'])->name('get.documentWaverView');
    Route::post('project/document-claim-data-2/{project_id}', [DocumentController::class, 'getDocumentClaimDataTwo'])->name('get.documentClaimDataTwo');
    Route::post('project/document-claim-data-complete/{project_id}', [DocumentController::class, 'getDocumentClaimDataComplete'])->name('get.documentClaimDataComplete');
    Route::get('project/credit-application/{project_id}', [DocumentController::class, 'getCreditApplication'])->name('get.creditApplication');
    Route::get('project/job-info/{project_id}', [DocumentController::class, 'getJobInfo'])->name('get.jobInfoView');
    Route::post('project/save-credit-application/{project_id}', [DocumentController::class, 'saveCreditApplication'])->name('post.creditApplicationSave');
    Route::get('project/joint-payment-authorization/{project_id}', [DocumentController::class, 'getJointPaymentAuthorization'])->name('get.jointPaymentAuthorization');
    Route::post('project/joint-payment-authorization-save/{project_id}', [DocumentController::class, 'saveJointPaymentAuthorization'])->name('post.jointPaymentAuthorizationSave');
    Route::get('project/document-unconditional-waiver-release/{project_id}', [DocumentController::class, 'getDocumentUnconditionalWaiverRelease'])->name('get.documentUnconditionalWaiverRelease');
    Route::post('project/document-unconditional-waiver-release-save/{project_id}', [DocumentController::class, 'saveDocumentUnconditionalWaiverRelease'])->name('post.documentUnconditionalWaiverRelease');
    Route::get('project/document-conditional-waiver/{project_id}', [DocumentController::class, 'getDocumentConditionalWaiver'])->name('get.documentConditionalWaiver');
    Route::get('project/document-conditional-waiver-final/{project_id}', [DocumentController::class, 'getDocumentConditionalWaiverFinal'])->name('get.documentConditionalWaiverFinal');
    Route::get('project/document-unconditional-waiver-final/{project_id}', [DocumentController::class, 'getDocumentUnconditionalWaiverFinal'])->name('get.documentUnconditionalWaiverFinal');
    Route::get('project/document-partial-waiver/{project_id}', [DocumentController::class, 'getDocumentPartialWaiver'])->name('get.documentPartialWaiver');
    Route::get('project/document-partial-waiver-date/{project_id}', [DocumentController::class, 'getDocumentPartialWaiverDate'])->name('get.documentPartialWaiverDate');
    Route::get('project/document-standard-waiver-final/{project_id}', [DocumentController::class, 'getDocumentStandardWaiverFinal'])->name('get.documentStandardWaiverFinal');
    Route::get('project/job-info-sheet/{project_id}', [DocumentController::class, 'getJobInfoSheet'])->name('get.job.info.sheet');
    Route::get('project/express/job-info-sheet/{project_id}', [DocumentController::class, 'getExpressJobInfoSheet'])->name('get.express.job.info.sheet');
    Route::get('project/owner-notice-sheet/{project_id}', [DocumentController::class, 'getOwnerNoticeSheet'])->name('get.owner.notice.sheet');

    //Member Invite Section
    Route::get('invite', [InviteController::class, 'getInvite'])->name('member.invite');
    Route::post('invite/post', [InviteController::class, 'postInvite'])->name('member.invite.post');

    //Member Contact Us Section
    Route::get('contact-us', [ContactController::class, 'getContactUs'])->name('member.contact.us');
    Route::post('contact-us/post', [ContactController::class, 'postContactUs'])->name('member.contact.us.post');
    Route::post('consultation/post', [ConsultationController::class, 'postConsultation'])->name('member.consultation.post');
    Route::get('consultation', [ConsultationController::class, 'getConsultation'])->name('member.get.consultation');
    Route::get('collect-receivables', [ConsultationController::class, 'getCollectReceivables'])->name('member.get.collect.receivables');

    //New Claim
    Route::get('new-claim', [ClaimController::class, 'newClaimStep1'])->name('admin.new.claim_step1');
    Route::get('new-claim-data-sheet', [ClaimController::class, 'getNewClaim'])->name('admin.new.claim_data_sheet_new');
    Route::get('new-claim-step2', [ClaimController::class, 'newClaimStep2'])->name('admin.new.claim_step2');
    Route::get('new-claim-step3', [ClaimController::class, 'newClaimStep3'])->name('admin.new.claim_step3');
    Route::get('new-claim-step4', [ClaimController::class, 'newClaimStep4'])->name('admin.new.claim_step4');
    Route::post('new-claim/submit', [ClaimController::class, 'submitNewClaim'])->name('submit.new.claim');
    Route::post('new-claim/save', [ClaimController::class, 'saveNewClaim'])->name('save.new.claim');
    Route::post('job-info/save/document', [LienController::class, 'saveJobDocument'])->name('save.new.job.document');
    Route::post('job-info/save', [LienController::class, 'saveJobInfo'])->name('save.new.job');
    Route::post('owner-notice/save', [LienController::class, 'saveOwnerNoticeForm'])->name('save.owner.notice');
    Route::post('add/job/info/file', [LienController::class, 'saveJobInfoFile'])->name('add.job.info.file');
    Route::post('add/job/file', [DocumentController::class, 'saveJobFile'])->name('add.job.file');
    Route::post('remove/job/info/file', [LienController::class, 'removeJobInfoFile'])->name('remove.job.info.file');
    Route::get('job-info/export/{project_id}', [LienController::class, 'exportJobInfo'])->name('get.jobInfoExport');
    Route::get('job-info/test/{project_id}', [LienController::class, 'exportJobInfoTest'])->name('get.jobInfoExport.test');
    Route::get('project/document/claim-form/{project_id}', [DocumentController::class, 'claimForm'])->name('get.claim.form');

    //Logout
    Route::get('logout', [UserController::class, 'logout'])->name('member.logout');
    Route::get('view/{file}', [DocumentController::class, 'getView'])->name('document.view');
    Route::get('autocomplete', [DocumentController::class, 'autoComplete'])->name('autocomplete');

    //Project name autocomplete route
    Route::get('project-autocomplete', [ProjectController::class, 'autoComplete'])->name('project.autocomplete');
    Route::get('auto-complete/contract', [DocumentController::class, 'autoCompleteContract'])->name('autocomplete.contract');
    Route::get('auto-complete/contact/company', [ContactController::class, 'autoCompleteCompanyDetails'])->name('autocomplete.contact.company');
    Route::get('auto-complete/contact/companyonrolechange', [ContactController::class, 'autoCompleteCompanyDetailsOnRoleChange'])->name('autocomplete.contact.company.rolechange');
    Route::get('contact/fetch-companies', [ContactController::class, 'fetchCompanies'])->name('fetch.companies');
    Route::get('auto-complete/company', [ContactController::class, 'autoCompleteCompany'])->name('autocomplete.company');
    Route::get('auto-complete/contact-first-name', [ContactController::class, 'autoCompleteContactFirstName'])->name('autocomplete.contact.firstname');
    Route::get('auto-complete/get-contact-details', [ContactController::class, 'getContactDetails'])->name('autocomplete.contact.details');
    Route::post('edit/job/description', [ProjectController::class, 'editJobDescription'])->name('edit.job.description');
    Route::post('get/contact/data', [ContactController::class, 'getContactData'])->name('get.contact.data');
    Route::post('get/all/sub-user-details', [UserController::class, 'getAllSubUserDetails'])->name('get.all.subuser.details');
});

//Authentication Lien Provider Route Groups
Route::group(['prefix' => 'lien', 'middleware' => ['lien', 'web']], function () {
    //Lien home Page
    Route::get('/', [UserController::class, 'lien'])->name('lien.dashboard');

    //Lien update profile
    Route::get('update/profile', [UserController::class, 'updateLienProfile'])->name('lien.update.profile');
    Route::post('update/profile/action', [UserController::class, 'updateLienProfileAction'])->name('lien.action.profile');
    Route::get('job-info', [LienController::class, 'lienJobInfoView'])->name('lien.view.jobinfos');
    Route::get('autocomplete/lien/company', [MemberController::class, 'autocompleteCompany'])->name('lien.company.autocomplete');

    //Member change password
    Route::post('change/password', [UserController::class, 'changePassword'])->name('lien.change.password');
    Route::get('view/project', [ProjectController::class, 'viewLienProject'])->name('lien.view.project');
    Route::post('view/project/task/update', [ProjectController::class, 'updateTask'])->name('lien.project.update.task');
    Route::get('logout', [UserController::class, 'logout'])->name('lien.logout');
    Route::get('project/document-claim-view/{project_id}/{flag}', [LienDocumentController::class, 'getDocumentClaimView'])->name('get.lien.documentClaimView');
    Route::get('project/document-credit-application-view/{project_id}/{flag}', [LienDocumentController::class, 'getDocumentCreditView'])->name('get.lien.documentCreditView');
    Route::get('project/document-joint-payment-view/{project_id}/{flag}', [LienDocumentController::class, 'getDocumentJointView'])->name('get.lien.documentJointView');
    Route::get('project/document-waver-view/{project_id}/{flag}', [LienDocumentController::class, 'getDocumentWaverView'])->name('get.lien.documentWaverView');
    Route::get('project/job-info-sheet/{project_id}', [LienDocumentController::class, 'getJobInfoSheet'])->name('get.lien.job.info.sheet');
    Route::get('job-info/export/{project_id}', [LienDocumentController::class, 'exportJobInfo'])->name('get.lien.jobInfoExport');
    Route::get('project/job-info-sheet/{project_id}', [LienDocumentController::class, 'getJobInfoSheet'])->name('get.lien.job.info.sheet');
    Route::get('project/line-bound-summary/{state}/{projectType}', [LienDocumentController::class, 'getLineBoundSummery'])->name('get.lien.lineBoundSummery');
});

//Invite Link
Route::get('user/invite/{id}', [InviteController::class, 'getLoginInvite'])->name('member.invite.url');
Route::post('user/registration/invite', [InviteController::class, 'postRegistration'])->name('member.invite.registration');
Route::post('consultation/post', [ConsultationController::class, 'postConsultation'])->name('member.consultation.post');
Route::get('consultation', [ConsultationController::class, 'getConsultation'])->name('member.get.consultation');

//Export Database
Route::get('export', [ExportController::class, 'deadline'])->name('export');
Route::get('export/line-bound-summary', [ExportController::class, 'lineBoundSummery'])->name('line.bound.summery');

Route::get('express-toggle', function() {

    $on_create_project_route = request()->has('new');

    if(session()->has('express') || request()->has('express')) {
        session()->forget('express');
        if($on_create_project_route) {
            return redirect()->route('member.create.project');
        }
    } else {
        session()->put('express', true);

        if($on_create_project_route) {
            return redirect()->route('member.create.express.project');
        }
    }

    return back();
})->name('express-toggle');
