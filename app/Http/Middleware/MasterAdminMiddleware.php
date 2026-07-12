<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterAdminMiddleware
{
    private const PERMISSION_MAP = [
        // Banners
        'admin.banners' => ['banners', 'Banners'],
        'admin.banners.store' => ['banners.add', 'add Banners'],
        'admin.banners.update' => ['banners.edit', 'edit Banners'],
        'admin.banners.toggle' => ['banners.edit', 'edit Banners'],
        'admin.banners.destroy' => ['banners.remove', 'delete Banners'],

        // Products
        'manageProducts' => ['products', 'Products'],
        'saveNewProduct' => ['products.add', 'add Products'],
        'updateProduct' => ['products.edit', 'edit Products'],
        'updateProductPrice' => ['products.edit', 'edit Products'],
        'product.toggleActive' => ['products.edit', 'edit Products'],
        'product.toggleFeatured' => ['products.edit', 'edit Products'],
        'product.delete' => ['products.delete', 'delete Products'],
        'product.images' => ['products', 'Products'],
        'product.image.delete' => ['products.edit', 'edit Products'],

        // Categories
        'manageCategory' => ['categories', 'Categories'],
        'saveNewCategory' => ['categories.add', 'add Categories'],
        'updateCategory' => ['categories.edit', 'edit Categories'],
        'category.delete' => ['categories.delete', 'delete Categories'],
        'manageSubCategory' => ['categories', 'Categories'],
        'saveNewSubCategory' => ['categories.add', 'add Sub-Categories'],
        'updateSubCategory' => ['categories.edit', 'edit Sub-Categories'],

        // Users
        'ecommerceCustomers' => ['users', 'Users'],
        'toggleUserStatus' => ['users.edit', 'edit Users'],
        'adminUserOrders' => ['users', 'Users'],
        'admin.customer.delete' => ['users.delete', 'delete Users'],

        // Orders
        'customerOrders' => ['orders', 'Orders'],
        'viewCustomerOrder' => ['orders', 'Orders'],
        'updateCustomerOrderStatus' => ['orders.manage', 'manage Orders'],
        'admin.order.approvePayment' => ['orders.manage', 'manage Orders'],
        'admin.order.rejectPayment' => ['orders.manage', 'manage Orders'],
        'admin.order.delete' => ['orders.manage', 'delete Orders'],

        // CP Interest
        'cpInterestList' => ['cp_interest', 'CP Interest Requests'],
        'approveCpInterest' => ['cp_interest.manage', 'approve CP Interest Requests'],
        'rejectCpInterest' => ['cp_interest.manage', 'reject CP Interest Requests'],

        // CP Partners
        'cpList' => ['cp_partners', 'CP Partners'],
        'addNewCp' => ['cp_partners.manage', 'add CP Partners'],
        'storeNewCp' => ['cp_partners.manage', 'add CP Partners'],
        'edit_cp' => ['cp_partners.manage', 'edit CP Partners'],
        'editCpQuery' => ['cp_partners.manage', 'edit CP Partners'],
        'cpDetail' => ['cp_partners', 'CP Partners'],
        'deleteCp' => ['cp_partners.manage', 'delete CP Partners'],
        'toggleCpStatus' => ['cp_partners.manage', 'edit CP Partners'],

        // CP Orders
        'pendingOrders' => ['cp_orders', 'CP Orders'],
        'manageOrdersAdmin' => ['cp_orders', 'CP Orders'],
        'viewSingleOrder' => ['cp_orders', 'CP Orders'],
        'save_order_pricing' => ['cp_orders.manage', 'manage CP Orders'],
        'approveInventoryRequest' => ['cp_orders.manage', 'manage CP Orders'],
        'cancelInventoryRequest' => ['cp_orders.manage', 'manage CP Orders'],
        'approveCpPayment' => ['cp_orders.manage', 'manage CP Orders'],
        'rejectCpPayment' => ['cp_orders.manage', 'manage CP Orders'],
        'approveRejectOrders' => ['cp_orders.manage', 'manage CP Orders'],
        'list_all_orders' => ['cp_orders', 'CP Orders'],

        // Secondary Admin management (master only)
        'manageSecondaryAdmins' => ['__master_only__', 'Secondary Admins'],
        'addSecondaryAdmin' => ['__master_only__', 'Secondary Admins'],
        'updateSecondaryAdminPermissions' => ['__master_only__', 'Secondary Admins'],
        'removeSecondaryAdmin' => ['__master_only__', 'Secondary Admins'],

        // User management (master only)
        'addNewUser' => ['__master_only__', 'Users'],
        'storeNewUser' => ['__master_only__', 'Users'],
        'userList' => ['__master_only__', 'Users'],
        'edit_user' => ['__master_only__', 'Users'],
        'update_user' => ['__master_only__', 'Users'],
        'updateUserStatus' => ['__master_only__', 'Users'],
        'deleteUser' => ['__master_only__', 'Users'],
        'resetUserPassword' => ['__master_only__', 'Users'],

        // CP Permissions (master only)
        'manageCpPermissions' => ['__master_only__', 'CP Permissions'],
        'updateCpPermissions' => ['__master_only__', 'CP Permissions'],

        // Inventory (master only)
        'addNewInventory' => ['__master_only__', 'Inventory'],
        'storeNewInventory' => ['__master_only__', 'Inventory'],
        'manageInventory' => ['__master_only__', 'Inventory'],
        'transferInventory' => ['__master_only__', 'Inventory'],
        'storeTransferInventory' => ['__master_only__', 'Inventory'],
        'invTxnsAdmin' => ['__master_only__', 'Inventory'],

        // Admin settings (master only)
        'manageTeam' => ['__master_only__', 'Admin Settings'],
        'storeTeam' => ['__master_only__', 'Admin Settings'],
        'updateTeam' => ['__master_only__', 'Admin Settings'],
        'newInstallationStory' => ['__master_only__', 'Admin Settings'],
        'storeStory' => ['__master_only__', 'Admin Settings'],
        'listStories' => ['__master_only__', 'Admin Settings'],
        'getStory' => ['__master_only__', 'Admin Settings'],
        'updateStory' => ['__master_only__', 'Admin Settings'],

        // Fund management (master only)
        'transferFundToCp' => ['__master_only__', 'Fund Management'],
        'storeFundTransfer' => ['__master_only__', 'Fund Management'],
        'deductFundFromCp' => ['__master_only__', 'Fund Management'],
        'storeFundDeduction' => ['__master_only__', 'Fund Management'],
        'fundTransactionList' => ['__master_only__', 'Fund Management'],
        'razorpayTransactions' => ['__master_only__', 'Fund Management'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role_id, [1, 2])) {
            abort(403, 'Unauthorized.');
        }

        if ($user->role_id == 1) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        if ($routeName && isset(self::PERMISSION_MAP[$routeName])) {
            [$permission, $label] = self::PERMISSION_MAP[$routeName];

            if ($permission === '__master_only__' || !$user->hasAdminPermission($permission)) {
                $message = "You are not allowed to access $label. Contact admin to grant this permission.";

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'permission_error' => true, 'message' => $message], 403);
                }

                return redirect()->back()->with('permission_error', $message);
            }
        }

        return $next($request);
    }
}
