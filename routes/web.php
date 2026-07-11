<?php

use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\CpInventoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ManualInstallationController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolarRequirementsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\ChannelPartnerMiddleware;
use App\Http\Middleware\MasterAdminMiddleware;
use App\Http\Middleware\WarehouseMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'dashBoardFunction'])->name('dashBoardFunction');
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/shop', [ProductController::class, 'shopPage'])->name('shop');
Route::get('/shop/category/{slug}', [ProductController::class, 'shopPage'])->name('shop.category');
Route::get('/featured', [ProductController::class, 'featuredPage'])->name('featured');
Route::get('/product/{slug}', [ProductController::class, 'showProduct'])->name('product.show');
Route::get('/print', [UserController::class, 'printQuotation'])->name('printQuotation');
Route::get('CpInterest', [UserController::class, 'CpInterest'])->middleware('auth')->name('CpInterest');
Route::post('QueryCpInterest', [UserController::class, 'QueryCpInterest'])->middleware('auth')->name('QueryCpInterest');
Route::get('installationPartner', [UserController::class, 'installationPartner'])->name('installationPartner');
Route::post('QueryInstallationPartner', [UserController::class, 'QueryInstallationPartner'])->name('QueryInstallationPartner');
Route::post('userQuoteQuery', [LeadController::class, 'userQuoteQuery'])->name('userQuoteQuery');
Route::get('contactUs', [UserController::class, 'contactUs'])->name('contactUs');
Route::get('ourTeam', [UserController::class, 'ourTeam'])->name('ourTeam');
Route::post('QueryContactUs', [UserController::class, 'QueryContactUs'])->name('QueryContactUs');
Route::get('allInstallationPhotos', [UserController::class, 'allInstallationPhotos'])->name('allInstallationPhotos');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Cart (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('order.checkout');
    Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('order.place');
    Route::get('/my-orders', [CheckoutController::class, 'myOrders'])->name('user.orders');
    Route::get('/my-orders/{id}', [CheckoutController::class, 'orderDetail'])->name('user.order.detail');
    Route::get('/order-payment/{id}', [CheckoutController::class, 'paymentPage'])->name('user.order.payment');
    Route::post('/order-payment/{id}/upload', [CheckoutController::class, 'uploadPaymentProof'])->name('user.order.payment.upload');
    Route::get('/order-success/{id}', [CheckoutController::class, 'orderSuccess'])->name('user.order.success');
    Route::post('/razorpay/callback', [CheckoutController::class, 'razorpayCallback'])->name('razorpay.callback');
    Route::get('/account', [UserController::class, 'account'])->name('user.account');
    Route::post('/account', [UserController::class, 'updateAccount'])->name('user.account.update');
});

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
    Route::get('/cp/{id}/detail', [UserController::class, 'cpDetail'])->name('cpDetail');
    Route::post('/cp/{id}/delete', [UserController::class, 'deleteCp'])->name('deleteCp');
    Route::post('/cp/{id}/toggle-status', [UserController::class, 'toggleCpStatus'])->name('toggleCpStatus');

    Route::get('/cp-permissions', [UserController::class, 'manageCpPermissions'])->name('manageCpPermissions');
    Route::post('/cp-permissions/{id}', [UserController::class, 'updateCpPermissions'])->name('updateCpPermissions');

    Route::get('/cp-interest-list', [UserController::class, 'cpInterestList'])->name('cpInterestList');
    Route::post('/cp-interest/{id}/approve', [UserController::class, 'approveCpInterest'])->name('approveCpInterest');
    Route::post('/cp-interest/{id}/reject', [UserController::class, 'rejectCpInterest'])->name('rejectCpInterest');

    Route::get('/secondary-admins', [UserController::class, 'manageSecondaryAdmins'])->name('manageSecondaryAdmins');
    Route::post('/secondary-admins/add', [UserController::class, 'addSecondaryAdmin'])->name('addSecondaryAdmin');
    Route::post('/secondary-admins/{id}/permissions', [UserController::class, 'updateSecondaryAdminPermissions'])->name('updateSecondaryAdminPermissions');
    Route::post('/secondary-admins/{id}/remove', [UserController::class, 'removeSecondaryAdmin'])->name('removeSecondaryAdmin');

    Route::get('/ecommerce-customers', [UserController::class, 'ecommerceCustomers'])->name('ecommerceCustomers');
    Route::post('/ecommerce-customers/{id}/toggle-status', [UserController::class, 'toggleUserStatus'])->name('toggleUserStatus');
    Route::get('/ecommerce-customers/{id}/orders', [UserController::class, 'adminUserOrders'])->name('adminUserOrders');
    Route::delete('/ecommerce-customers/{id}', [UserController::class, 'deleteEcommerceCustomer'])->name('admin.customer.delete');

    Route::get('/manageCategory', [ProductController::class, 'manageCategory'])->name('manageCategory');
    Route::get('/manageSubCategory', [ProductController::class, 'manageSubCategory'])->name('manageSubCategory');
    Route::get('/manageProducts', [ProductController::class, 'manageProducts'])->name('manageProducts');
    Route::post('saveNewCategory', [ProductController::class, 'saveNewCategory'])->name('saveNewCategory');
    Route::post('/updateCategory', [ProductController::class, 'updateCategory'])->name('updateCategory');
    Route::post('saveNewSubCategory', [ProductController::class, 'saveNewSubCategory'])->name('saveNewSubCategory');
    Route::post('/updateSubCategory', [ProductController::class, 'updateSubCategory'])->name('updateSubCategory');
    Route::post('/saveNewProduct', [ProductController::class, 'saveNewProduct'])->name('saveNewProduct');
    Route::post('/updateProduct', [ProductController::class, 'updateProduct'])->name('updateProduct');
    Route::post('/updateProductPrice', [ProductController::class, 'updateProductPrice'])->name('updateProductPrice');
    Route::post('/products/{id}/toggle-active', [ProductController::class, 'toggleProductActive'])->name('product.toggleActive');
    Route::post('/products/{id}/toggle-featured', [ProductController::class, 'toggleProductFeatured'])->name('product.toggleFeatured');
    Route::get('/products/{id}/images', [ProductController::class, 'getProductImages'])->name('product.images');
    Route::delete('/product-images/{id}', [ProductController::class, 'deleteProductImage'])->name('product.image.delete');
    Route::delete('/categories/{id}', [ProductController::class, 'deleteCategory'])->name('category.delete');
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
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
    Route::get('/admin-inv-txnx', [InventoryController::class, 'invTxnsAdmin'])->name('invTxnsAdmin');


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
    Route::get('/manageOrdersAdmin', [OrderController::class, 'manageOrdersAdmin'])->name('manageOrdersAdmin');
    Route::get('/pendingOrders', [OrderController::class, 'pendingOrders'])->name('pendingOrders');
    Route::get('/allOrders', [OrderController::class, 'allOrders'])->name('list_all_orders');
    Route::get('/viewSingleOrder/{id}', [OrderController::class, 'viewSingleOrder'])->name('viewSingleOrder');
    Route::post('/saveOrderPricing', [OrderController::class, 'saveOrderPricing'])->name('save_order_pricing');
    Route::post('/inventory-request/{id}/approve', [OrderController::class, 'approveInventoryRequest'])->name('approveInventoryRequest');
    Route::post('/inventory-request/{id}/cancel', [OrderController::class, 'cancelInventoryRequest'])->name('cancelInventoryRequest');
    Route::get('/approveRejectOrders', [OrderController::class, 'approveRejectOrders'])->name('approveRejectOrders');
    Route::get('/customer-orders', [OrderController::class, 'customerOrderList'])->name('customerOrders');
    Route::get('/customer-orders/{id}', [OrderController::class, 'viewCustomerOrder'])->name('viewCustomerOrder');
    Route::post('/customer-orders/{id}/status', [OrderController::class, 'updateCustomerOrderStatus'])->name('updateCustomerOrderStatus');
    Route::post('/customer-orders/{id}/approve-payment', [OrderController::class, 'approvePayment'])->name('admin.order.approvePayment');
    Route::post('/customer-orders/{id}/reject-payment', [OrderController::class, 'rejectPayment'])->name('admin.order.rejectPayment');
    Route::delete('/customer-orders/{id}', [OrderController::class, 'deleteCustomerOrder'])->name('admin.order.delete');
    Route::get('/razorpay-transactions', [OrderController::class, 'razorpayTransactions'])->name('razorpayTransactions');

    // Banner routes
    Route::get('/banners', [BannerController::class, 'index'])->name('admin.banners');
    Route::post('/banners', [BannerController::class, 'store'])->name('admin.banners.store');
    Route::post('/banners/{banner}', [BannerController::class, 'update'])->name('admin.banners.update');
    Route::post('/banners/{banner}/toggle', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle');
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
});

Route::prefix('channel-partner')->middleware(['auth', ChannelPartnerMiddleware::class])->group(function () {
    Route::get('/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('cp.getSubCategory');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('cp.getProducts');

    Route::middleware(ChannelPartnerMiddleware::class.':new_request')->group(function () {
        Route::get('/cp-new-order', [OrderController::class, 'newOrderCp'])->name(name: 'newOrderCp');
        Route::post('/cp-new-order', [OrderController::class, 'storeNewOrderRequest'])->name(name: 'storeNewOrderRequest');
    });

    Route::middleware(ChannelPartnerMiddleware::class.':view_requests')->group(function () {
        Route::get('/cp-order-report', [OrderController::class, 'orderReportCp'])->name(name: 'orderReportCp');
        Route::get('/viewSingleOrderCp/{id}', [OrderController::class, 'viewSingleOrderCp'])->name('viewSingleOrderCp');
    });

    Route::get('/product-pricing', [OrderController::class, 'productPricing'])->name(name: 'productPricing')->middleware(ChannelPartnerMiddleware::class.':product_pricing');

    Route::middleware(ChannelPartnerMiddleware::class.':view_inventory')->group(function () {
        Route::get('/cp-inventory', [CpInventoryController::class, 'cpInventory'])->name(name: 'cpInventory');
    });

    Route::middleware(ChannelPartnerMiddleware::class.':transfer_inventory')->group(function () {
        Route::get('/transfer-inventory', [CpInventoryController::class, 'transferInventoryCp'])->name(name: 'transferInventoryCp');
        Route::post('/transfer-inventory', [CpInventoryController::class, 'storeTransferInventoryCp'])->name('storeTransferInventoryCp');
    });

    Route::get('/cp-inv-txnx', [CpInventoryController::class, 'invTxnsCp'])->name('invTxnsCp')->middleware(ChannelPartnerMiddleware::class.':inventory_transactions');

    Route::middleware(ChannelPartnerMiddleware::class.':manual_installations')->group(function () {
        Route::get('/new-manual-entry', [ManualInstallationController::class, 'newEntry'])->name(name: 'newManualEntry');
        Route::post('/store-manual-installation', [ManualInstallationController::class, 'storeManualInstallation'])->name('storeManualInstallation');
        Route::get('/my-manual-entries', [ManualInstallationController::class, 'myManualEntries'])->name(name: 'myManualEntries');
    });
    
});

Route::prefix('warehouse')->middleware(['auth', WarehouseMiddleware::class])->group(function () {
    // Route::get('/cp-new-order', [WarehouseController::class, 'newOrderCp'])->name(name: 'newOrderCp');
    // Route::post('/cp-new-order', [WarehouseController::class, 'storeNewOrderRequest'])->name(name: 'storeNewOrderRequest');
    // Route::get('/cp-order-report', [WarehouseController::class, 'orderReportCp'])->name(name: 'orderReportCp');
    // Route::get('/viewSingleOrderCp/{id}', [WarehouseController::class, 'viewSingleOrderCp'])->name('viewSingleOrderCp');
    // Route::get('/product-pricing', [WarehouseController::class, 'productPricing'])->name(name: 'productPricing');
    Route::get('/warehouse-inventory', [WarehouseController::class, 'wareHouseInventory'])->name(name: 'warehouseInventory');
    Route::get('/transfer-inventory', [WarehouseController::class, 'transferInventoryWarehouse'])->name(name: 'transferInventoryWarehouse');
    Route::post('/transfer-inventory', [WarehouseController::class, 'storeTransferInventoryWarehouse'])->name('storeTransferInventoryWarehouse');
    Route::get('/warehouse-inv-txnx', [WarehouseController::class, 'invTxnsWarehouse'])->name('invTxnsWarehouse');

    
});

require __DIR__ . '/auth.php';
