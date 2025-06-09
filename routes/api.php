<?php

use App\Http\Controllers\MobileAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OfficeEquipmentsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\EquipmentCopiesController;
use App\Http\Controllers\OfficeSuppliesController;
use App\Http\Controllers\BorrowTransactionItemsController;
use App\Http\Controllers\BorrowTransactionsController;
use App\Http\Controllers\InventoryAccessController;
use App\Http\Controllers\BorrowersController;
use App\Http\Controllers\OfficesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\RoadController;

// 911 Specific Controllers
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\TypeOfAssistanceController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\ActionsTakenController;
use App\Http\Controllers\UrgencyController;
use App\Http\Controllers\HotlineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\FileUploadController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::apiResource('/image', ImageController::class)
    ->only(['index', 'store', 'destroy']);

Route::get('/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// auth for flutter
Route::prefix('mobile')->controller(MobileAuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware(['api.key'])->group(function () {

    // user api
    Route::get('/users', [UserController::class, 'index']);

    Route::post('/users', [UserController::class, 'store']);
    
    Route::post('/addUsers', [UserController::class, 'storeUser']);

    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // inventory access api
    Route::get('/inventory_access', [InventoryAccessController::class, 'index']);

    Route::put('/inventory_access/{inventoryAccess}', [InventoryAccessController::class, 'update']);

    Route::post('/inventory_access', [InventoryAccessController::class, 'store']);

    // Office Equipment api
    Route::get('/office_equipments', [OfficeEquipmentsController::class, 'index']);

    Route::post('/office_equipments', [OfficeEquipmentsController::class, 'store']);

    Route::get('/office_equipments/{officeEquipments}', [OfficeEquipmentsController::class, 'show']);

    Route::put('/office_equipments/{officeEquipments}', [OfficeEquipmentsController::class, 'update']);

    Route::delete('/office_equipments/{officeEquipments}', [OfficeEquipmentsController::class, 'destroy']);

    // categories api
    Route::get('/categories', [CategoriesController::class, 'index']);

    Route::post('/categories', [CategoriesController::class, 'store']);

    Route::get('/categories/{categories}', [CategoriesController::class, 'show']);

    Route::put('/categories/{categories}', [CategoriesController::class, 'update']);

    Route::delete('categories/{categories}', [CategoriesController::class, 'destroy']);

    // office supplies API
    Route::get('/office_supplies', [OfficeSuppliesController::class, 'index']);

    Route::post('/office_supplies', [OfficeSuppliesController::class, 'store']);

    Route::get('/office_supplies/{officeSupplies}', [OfficeSuppliesController::class, 'show']);

    Route::put('/office_supplies/{officeSupplies}', [OfficeSuppliesController::class, 'update']);

    Route::delete('/office_supplies/{officeSupplies}', [OfficeSuppliesController::class, 'destroy']);

    // equipment copies API
    Route::get('/equipment_copies', [EquipmentCopiesController::class, 'index']);

    Route::post('/equipment_copies', [EquipmentCopiesController::class, 'store']);

    Route::get('/equipment_copies/{equipmentCopies}', [EquipmentCopiesController::class, 'show']);

    Route::put('/equipment_copies/{equipmentCopies}', [EquipmentCopiesController::class, 'update']);

    Route::delete('/equipment_copies/{equipmentCopies}', [EquipmentCopiesController::class, 'destroy']);

    //borrow transaction items API
    Route::get('/borrow_transaction_items', [BorrowTransactionItemsController::class, 'index']);

    Route::post('/borrow_transaction_items', [BorrowTransactionItemsController::class, 'store']);

    Route::get('/borrow_transaction_items/{borrowTransactionItems}', [BorrowTransactionItemsController::class, 'show']);

    Route::put('/borrow_transaction_items/{borrowTransactionItems}', [BorrowTransactionItemsController::class, 'update']);

    Route::delete('/borrow_transaction_items/{borrowTransactionItems}', [BorrowTransactionItemsController::class, 'destroy']);

    //borrowers transactions API
    Route::get('/borrow_transactions', [BorrowTransactionsController::class, 'index']);

    Route::post('/borrow_transactions', [BorrowTransactionsController::class, 'store']);

    Route::get('/borrow_transactions/{borrowTransactions}', [BorrowTransactionsController::class, 'show']);

    Route::put('/borrow_transactions/{borrowTransactions}', [BorrowTransactionsController::class, 'update']);

    Route::delete('/borrow_transactions/{borrowTransactions}', [BorrowTransactionsController::class, 'destroy']);

    // borrowers API
    Route::get('/borrowers', [BorrowersController::class, 'index']);

    Route::post('/borrowers', [BorrowersController::class, 'store']);

    Route::get('/borrowers/{borrowers}', [BorrowersController::class, 'show']);

    Route::put('/borrowers/{borrowers}', [BorrowersController::class, 'update']);

    Route::delete('/borrowers/{borrowers}', [BorrowersController::class, 'destroy']);

    //Offices API
    Route::get('/offices', [OfficesController::class, 'index']);

    Route::post('/offices', [OfficesController::class, 'store']);

    Route::get('/offices/{offices}', [OfficesController::class, 'show']);

    Route::put('/offices/{offices}', [OfficesController::class, 'update']);

    Route::delete('/offices/{offices}', [OfficesController::class, 'destroy']);

    //Transaction History
    Route::get('/transaction_history', [TransactionHistoryController::class, 'index']);

    Route::put('/transaction_history/{transactionHistory}', [TransactionHistoryController::class, 'update']);



    Route::prefix('911')->group(function () {

        # 📊 Dashboard Controlller Routes
        Route::get('/stacked-bar-chart', [DashboardController::class, 'stackedBarChart']);

        Route::get('/bar-chart', [DashboardController::class, 'barChart']);

        Route::get('/pie-chart', [DashboardController::class, 'pieChart']);

        Route::get('/heat-map', [DashboardController::class, 'heatMap']);

        Route::get('/recent', [DashboardController::class, 'recent']);

        Route::get('/maps', [DashboardController::class, 'maps']);

        Route::get('/total-report', [DashboardController::class, 'totalReport']);

        Route::get('/growth', [DashboardController::class, 'growth']);

        Route::get('/most-cases', [DashboardController::class, 'mostCases']);


        # 🏙 Barangay Controller Routes
        Route::get('/barangay', [BarangayController::class, 'index']);

        Route::get('/barangay-pagination', [BarangayController::class, 'pagination']);

        Route::get('/barangay-fetch/{id}', [BarangayController::class, 'show']);

        Route::put('/barangay-archive/{id}', [BarangayController::class, 'archive']);


        # 📝 Report Controller Routes
        Route::get('/report', [ReportController::class, 'index']);
        Route::get('/report-pagination', [ReportController::class, 'pagination']);

        Route::post('/report', [ReportController::class, 'store']);

        Route::patch('/restore-report/{id}', [ReportController::class, 'restore']);

        Route::put('/report/{id}', [ReportController::class, 'update']);

        Route::get('/report-fetch/{id}', [ReportController::class, 'show']);

        Route::put('/report-archive/{id}', [ReportController::class, 'archive']);

        Route::put('/report-multiple-archive', [ReportController::class, 'archiveMultiple']);

        Route::get('/related-incidents-pagination', [ReportController::class, 'relatedIncidentsPagination']);

        Route::get('/related-incidents', [ReportController::class, 'relatedIncidentsGenerate']);


        # 👤 User Controller Routes
        Route::get('/users-active', [UserController::class, 'usersActive']);

        Route::get('/users-archived', [UserController::class, 'usersArchived']);

        Route::patch('user-dashboard-role/{id}', [UserController::class, 'dashboard']);

        Route::patch('user-archive/{id}', [UserController::class, 'archive']);

        Route::put('/user/{user}', [UserController::class, 'updateUserFor911']);

        Route::post('/users-create', [UserController::class, 'adminCreate']);


        # 📁 Upload Controller Routes
        Route::post('/import-excel-data', [FileUploadController::class, 'store']);

        Route::post('/import-excel', [FileUploadController::class, 'read']);


        # 📊 Audit Controller Routes
        Route::get('/audit', [AuditController::class, 'index']);

        Route::get('/audit-pagination', [AuditController::class, 'pagination']);


        # 📞 Hotline Controller Routes
        Route::apiResource('/emergency-contacts', HotlineController::class);

        Route::get('/emergency-contacts-pagination', [HotlineController::class, 'pagination']);

        Route::put('/emergency-contacts-update/{id}', [HotlineController::class, 'update']);

        Route::put('/emergency-contacts-archive/{id}', [HotlineController::class, 'archive']);

        Route::patch('/restore-emergency-contact/{id}', [HotlineController::class, 'restore']);


        # 📝 Actions Taken Controller Routes
        Route::apiResource('/action-taken', ActionsTakenController::class);

        Route::get('/action-taken-pagination', [ActionsTakenController::class, 'pagination']);

        Route::put('/action-taken-archive/{id}', [ActionsTakenController::class, 'archive']);


        # 📝 Incident Controller Routes
        Route::apiResource('/incident', IncidentController::class);

        Route::get('/incident-pagination', [IncidentController::class, 'pagination']);

        Route::put('/incident-archive/{id}', [IncidentController::class, 'archive']);


        # 📝 Type of Assistance Controller Routes
        Route::apiResource('/assistance', TypeOfAssistanceController::class);

        Route::get('/assistance-pagination', [TypeOfAssistanceController::class, 'pagination']);

        Route::put('/assistance-archive/{id}', [TypeOfAssistanceController::class, 'archive']);


        # 📝 Source Controller Routes
        Route::apiResource('/source', SourceController::class);

        Route::get('/source-pagination', [SourceController::class, 'pagination']);

        Route::put('/source-archive/{id}', [SourceController::class, 'archive']);


        # 📝 Urgency Controller Routes
        Route::apiResource('/urgency', UrgencyController::class);

        Route::get('/urgency-pagination', [UrgencyController::class, 'pagination']);

        Route::put('/urgency-archive/{id}', [UrgencyController::class, 'archive']);
    });

    Route::prefix('traffic-tracking')->group(function () {

        Route::post('/roads', [RoadController::class, 'store']);
        # Traffic Road Controller Routes
        Route::get('/roads', [RoadController::class, 'index']);
        // Add this new route for fetching road types
        Route::get('/road-types', [RoadController::class, 'getRoadTypes']);

        // Existing routes...
        Route::post('/roads', [RoadController::class, 'store']);
        Route::get('/roads', [RoadController::class, 'index']);
        // EXISTING Update coordinates routes
        Route::put('/inbound-coordinates/{road}', [RoadController::class, 'updateInboundCoordinates']);
        Route::put('/outbound-coordinates/{road}', [RoadController::class, 'updateOutboundCoordinates']);

        // ADD THESE NEW routes for getting coordinates
        Route::get('/inbound-coordinates/{road}', [RoadController::class, 'getInboundCoordinates']);
        Route::get('/outbound-coordinates/{road}', [RoadController::class, 'getOutboundCoordinates']);

        // CORRECTED ROUTE - single route for road updates
        Route::put('/road/{road}', [RoadController::class, 'update']);

        # Traffic status routes
        Route::put('/inbound/{road}', [RoadController::class, 'updateInbound']);
        Route::put('/outbound/{road}', [RoadController::class, 'updateOutbound']);

        # Soft Delete
        Route::delete('/soft-delete/{road}', [RoadController::class, 'softDelete']);
    });
});
