<?php

use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolarRequirementsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Middleware\ChannelPartnerMiddleware;
use App\Http\Middleware\MasterAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [UserController::class, 'dashBoardFunction'])->name('dashBoardFunction');
Route::get('/print', [UserController::class, 'printQuotation'])->name('printQuotation');
Route::get('CpInterest', [UserController::class, 'CpInterest'])->name('CpInterest');
Route::get('installationPartner', [UserController::class, 'installationPartner'])->name('installationPartner');
Route::post('QueryCpInterest', [UserController::class, 'QueryCpInterest'])->name('QueryCpInterest');
Route::post('QueryInstallationPartner', [UserController::class, 'QueryInstallationPartner'])->name('QueryInstallationPartner');
Route::post('userQuoteQuery', [LeadController::class, 'userQuoteQuery'])->name('userQuoteQuery');
Route::get('contactUs', [UserController::class, 'contactUs'])->name('contactUs');
Route::get('ourTeam', [UserController::class, 'ourTeam'])->name('ourTeam');
Route::post('QueryContactUs', [UserController::class, 'QueryContactUs'])->name('QueryContactUs');
Route::get('allInstallationPhotos', [UserController::class, 'allInstallationPhotos'])->name('allInstallationPhotos');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashBoardFunction'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('admin')->middleware(['auth', MasterAdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [UserController::class, 'masterAdminDashboard'])->name('masterAdminDashboard');
    Route::get('/addNewUser', [UserController::class, 'addNewUser'])->name('addNewUser');
    Route::post('/addNewUser', [UserController::class, 'storeNewUser'])->name('storeNewUser');
    Route::get('/userList', [UserController::class, 'userList'])->name('userList');
    Route::get('edit_user/{id?}', [UserController::class, 'edit_user'])->name('edit_user');
    Route::post('update_user', [UserController::class, 'update_user'])->name('update_user');

    Route::post('updateUserStatus/', [UserController::class, 'updateUserStatus'])->name('updateUserStatus');
    Route::put('deleteUser/{id?}', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::get('resetUserPassword/{id?}', [UserController::class, 'resetUserPassword'])->name('resetUserPassword');


    Route::get('/addNewCp', [UserController::class, 'addNewCp'])->name('addNewCp');
    Route::post('/addNewCp', [UserController::class, 'storeNewCp'])->name('storeNewCp');
    Route::get('/cpList', [UserController::class, 'cpList'])->name('cpList');
    Route::get('edit_cp/{id?}', [UserController::class, 'edit_cp'])->name('edit_cp');
    route::post('editCpQuery/', [UserController::class, 'editCpQuery'])->name('editCpQuery');



    Route::get('/manageCategory', [ProductController::class, 'manageCategory'])->name('manageCategory');
    Route::get('/manageSubCategory', [ProductController::class, 'manageSubCategory'])->name('manageSubCategory');
    Route::get('/manageProducts', [ProductController::class, 'manageProducts'])->name('manageProducts');
    Route::post('saveNewCategory', [ProductController::class, 'saveNewCategory'])->name('saveNewCategory');
    Route::post('/updateCategory', [ProductController::class, 'updateCategory'])->name('updateCategory');
    Route::post('saveNewSubCategory', [ProductController::class, 'saveNewSubCategory'])->name('saveNewSubCategory');
    Route::post('/updateSubCategory', [ProductController::class, 'updateSubCategory'])->name('updateSubCategory');
    Route::post('/saveNewProduct', [ProductController::class, 'saveNewProduct'])->name('saveNewProduct');
    Route::post('/updateProduct', [ProductController::class, 'updateProduct'])->name('updateProduct');
    Route::get('/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('getSubCategory');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('getProducts');
    Route::get('/get-channelPartnerByRole', [ProductController::class, 'getChannelPartnerByRole'])->name('getChannelPartnerByRole');


    //////////////////---- invenrtory routes ----/////////////////////
    Route::get('/add-new-inventory', [InventoryController::class, 'addNewInventory'])->name('addNewInventory');
    Route::post('/add-new-inventory', [InventoryController::class, 'storeNewInventory'])->name('storeNewInventory');
    Route::get('/manage-inventory', [InventoryController::class, 'manageInventory'])->name('manageInventory');
    Route::get('/transfer-inventory', [InventoryController::class, 'transferInventory'])->name('transferInventory');
    Route::post('/transfer-inventory', [InventoryController::class, 'storeTransferInventory'])->name('storeTransferInventory');
    Route::get('/get-product-availableQty', [InventoryController::class, 'getProductAvailableQty'])->name('getProductAvailableQty');
    Route::get('/get-available-serial', [InventoryController::class, 'getAvailableSerial'])->name('getAvailableSerial');


    Route::get('/manage-team', [AdminSettingController::class, 'manageTeam'])->name('manageTeam');
    Route::post('/store-team', [AdminSettingController::class, 'storeTeam'])->name('storeTeam');
    Route::post('/update-team', [AdminSettingController::class, 'updateTeam'])->name('updateTeam');
    Route::get('/new-installations-story', [AdminSettingController::class, 'newInstallationStory'])->name('newInstallationStory');
    Route::post('/store-story', [AdminSettingController::class, 'storeStory'])->name('storeStory');
    Route::get('/list-stories', [AdminSettingController::class, 'listStories'])->name('listStories');
    Route::get('/get-story/{id}', [AdminSettingController::class, 'show'])->name('getStory');
    Route::put('/update-story/{id}', [AdminSettingController::class, 'update'])->name('updateStory');

    Route::get('/transfer-fund-cp', [WalletController::class, 'transferFundToCp'])->name('transferFundToCp');
    Route::post('/', [WalletController::class, 'storeFundTransfer'])->name('storeFundTransfer');

    Route::get('/transfer-fund-cp', [WalletController::class, 'transferFundToCp'])->name('transferFundToCp');
    Route::get('/deduct-fund-cp', [WalletController::class, 'deductFundFromCp'])->name('deductFundFromCp');
    Route::post('/deduct-fund-cp', [WalletController::class, 'storeFundDeduction'])->name('storeFundDeduction');

    Route::get('/fundTransactionList', [WalletController::class, 'fundTransactionList'])->name('fundTransactionList');



});

Route::prefix('channel-partner')->middleware(['auth', ChannelPartnerMiddleware::class])->group(function () {

});

require __DIR__ . '/auth.php';
