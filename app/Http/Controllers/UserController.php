<?php

namespace App\Http\Controllers;

use App\Mail\CpRegistrationEmail;
use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\Banner;
use App\Models\InstallationStory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\InstallationVideo;
use App\Models\MyTeam;
use App\Models\OurInstallation;
use App\Models\Role;
use App\Models\SolarTeam;
use App\Models\User;
use App\Models\UserQuery;
use App\Models\UserRole;
use App\Models\UserSolarQuotation;
use App\Models\CpInterest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function CpInterest()
    {
        $states = $this->getStates();
        $cities = $this->getCities();
        return view('publicPages.channelPartnerEnrollment')
            ->with('states', $states)
            ->with('cities', $cities);
    }

    public function QueryCpInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'companyName' => 'required|string|max:255',
            'contactPerson' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|size:10',
            'state' => 'required|string',
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CpInterest::create([
            'company_name' => $request->companyName,
            'contact_person' => $request->contactPerson,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'state' => $request->state,
            'city' => $request->city,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Your interest has been submitted! Our team will contact you soon.');
    }

    public function installationPartner()
    {
        $states = $this->getStates();
        $cities = $this->getCities();
        return view('publicPages.installationPartnerEnrollment')
            ->with('states', $states)
            ->with('cities', $cities);
    }

    public function ourTeam()
    {
        $teamMembers = SolarTeam::where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('publicPages.solarTeam')->with('teamMembers', $teamMembers);
    }

    public function allInstallationPhotos()
    {
        $installations = InstallationStory::where('active_status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('publicPages.installationPhotoVideo')->with('installations', $installations);
    }

    public function contactUs()
    {
        return view('publicPages.contactUs');
    }
    public function adminDashboard()
    {
        try {
            return view('Admin.adminDashboard');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function dashBoardFunction()
    {
        $banners = Banner::active()->get();
        $categories = ProductCategory::withCount(['products' => fn($q) => $q->where('is_active', true)])->get();
        $featuredProducts = Product::featured()->with('category')->latest()->take(8)->get();
        $allProducts = Product::active()->with('category')->latest()->take(12)->get();

        return view('dashboard.index', compact('banners', 'categories', 'featuredProducts', 'allProducts'));
    }

    public function masterAdminDashboard()
    {
        $totalCustomers  = User::where('role_id', 3)->count();
        $totalRevenue    = \App\Models\CustomerOrder::where('payment_status', 'paid')->sum('total_amount');
        $ordersToday     = \App\Models\CustomerOrder::whereDate('created_at', today())->count();
        $pendingOrders   = \App\Models\CustomerOrder::where('status', 'pending')->count();
        $totalProducts   = Product::count();
        $totalCPs        = \App\Models\ChannelPartner::count();

        // Monthly revenue – last 6 months
        $monthlyRevenue = collect(range(5, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'label'   => $month->format('M Y'),
                'revenue' => \App\Models\CustomerOrder::where('payment_status', 'paid')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_amount'),
            ];
        });

        // Orders by status
        $orderStatusCounts = \App\Models\CustomerOrder::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Top 5 products by quantity sold
        $topProducts = \App\Models\CustomerOrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent 5 orders
        $recentOrders = \App\Models\CustomerOrder::with('user')->latest()->limit(5)->get();

        return view('Admin.adminDashboard', compact(
            'totalCustomers', 'totalRevenue', 'ordersToday', 'pendingOrders',
            'totalProducts', 'totalCPs', 'monthlyRevenue', 'orderStatusCounts',
            'topProducts', 'recentOrders'
        ));
    }

    public function account()
    {
        return view('user.account');
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'          => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'state'         => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'city'          => 'nullable|string|max:100',
            'pincode'       => 'nullable|string|max:10',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        $user->name          = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->address       = $request->address;
        $user->state         = $request->state;
        $user->district      = $request->district;
        $user->city          = $request->city;
        $user->pincode       = $request->pincode;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', 'Account updated successfully.');
    }


    public function addNewUser()
    {
        $roles = Role::where('id', '!=', 1)->get();
        $cp_list = ChannelPartner::where('active_status', 1)->get();
        return view('Admin.userSetting.addNewUser')
            ->with('roles', $roles)
            ->with('cp_list', $cp_list);
    }

    public function storeNewUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile_number = $request->input('mobile');
            $user->password = Hash::make($request->input('password'));
            $user->role_id = $request->input('role_id');
            $user->cp_id = $request->input('cp_id') ?? null; // Set cp_id if provided, otherwise set to null
            $user->save();

            return redirect()->route('addNewUser')->with('success', 'New user added successfully.');
        } catch (Exception $e) {
            Log::error('Error adding new user: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function userList()
    {
        $users = User::with(['role', 'channelPartner'])
            ->where('role_id', '!=', 1)
            ->get();

        return view('Admin.userSetting.userList')->with('users', $users);
    }

    public function edit_user($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('id', '!=', 1)->get();
        $cp_list = ChannelPartner::where('active_status', 1)->get();

        return view('Admin.userSetting.editUser')
            ->with('user', $user)
            ->with('roles', $roles)
            ->with('cp_list', $cp_list);
    }

    public function update_user(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->input('id'),
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::findOrFail($request->input('id'));
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile_number = $request->input('mobile');
            $user->role_id = $request->input('role_id');
            $user->cp_id = $request->input('cp_id') ?? null; // Update cp_id if provided, otherwise set to null
            $user->save();

            return redirect()->route('edit_user', ['id' => $user->id])->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function addNewCp()
    {
        $cp_roles = ChannelPartnerRole::all();
        $states = $this->getStates();
        $cities = $this->getCities();

        return view('Admin.cpSetting.addNewCp')
            ->with('cp_roles', $cp_roles)
            ->with('states', $states)
            ->with('cities', $cities);
    }

    public function storeNewCp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cp_name' => 'required|string|max:255',
            'cp_email' => 'required|string|email|max:255|unique:channel_partners,email',
            'mobile' => 'required|string|max:15',
            'role_id' => 'required|exists:channel_partner_roles,id',
            'state' => 'required|string|in:' . implode(',', $this->getStates()),
            'city' => 'required|string|in:' . implode(',', $this->getCities()),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $cp = new ChannelPartner();
            $cp->cp_name = $request->input('cp_name');
            $cp->contact_person = $request->input('contact_person');
            $cp->email = $request->input('cp_email');
            $cp->phone_number = $request->input('mobile');
            $cp->full_address = $request->input('full_address');
            $cp->city = $request->input('city');
            $cp->state = $request->input('state');
            $cp->zip_code = $request->input('pin_code');
            $cp->cp_role = $request->input('role_id');
            $cp->active_status = 1;
            $cp->save();

            // Send registration email to the channel partner
            // Mail::to(users: $cp->email)->send(new CpRegistrationEmail($cp));

            return redirect()->route('addNewCp')->with('success', 'New Channel Partner added successfully.');
        } catch (Exception $e) {
            Log::error('Error adding new Channel Partner: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function cpList()
    {
        $cp_list = ChannelPartner::with('role', 'associateUsers', 'wallet')->get();
        return view('Admin.cpSetting.cpList')->with('cp_list', $cp_list);
    }

    public function edit_cp($id)
    {
        $cp = ChannelPartner::findOrFail($id);
        $cp_roles = ChannelPartnerRole::all();
        $states = $this->getStates();
        $cities = $this->getCities();

        return view('Admin.cpSetting.editCp')
            ->with('cp', $cp)
            ->with('cp_roles', $cp_roles)
            ->with('states', $states)
            ->with('cities', $cities);
    }

    public function editCpQuery(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:channel_partners,id',
            'cp_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'role_id' => 'required|exists:channel_partner_roles,id',
            'state' => 'required|string|in:' . implode(',', $this->getStates()),
            'city' => 'required|string|in:' . implode(',', $this->getCities()),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $cp = ChannelPartner::findOrFail($request->input('id'));
            $cp->cp_name = $request->input('cp_name');
            $cp->contact_person = $request->input('contact_person');
            $cp->email = $request->input('cp_email');
            $cp->phone_number = $request->input('mobile');
            $cp->full_address = $request->input('full_address');
            $cp->city = $request->input('city');
            $cp->state = $request->input('state');
            $cp->zip_code = $request->input('pin_code');
            $cp->cp_role = $request->input('role_id');
            $cp->save();

            return redirect()->route('edit_cp', ['id' => $cp->id])->with('success', 'Channel Partner updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating Channel Partner: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function manageCpPermissions()
    {
        $cpUsers = User::whereHas('role', fn($q) => $q->where('name', 'channel_partner'))->get();
        return view('Admin.cpSetting.cpPermissions', compact('cpUsers'));
    }

    public function updateCpPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->cp_permissions = $request->input('permissions', []);
        $user->save();

        return redirect()->route('manageCpPermissions')->with('success', 'Permissions updated for ' . $user->name);
    }

    public function manageSecondaryAdmins()
    {
        abort_unless(auth()->user()->role?->name === 'master_admin', 403);
        $secondaryAdmins = User::where('role_id', 2)->with('role')->get();
        return view('Admin.userSetting.manageSecondaryAdmins', compact('secondaryAdmins'));
    }

    public function addSecondaryAdmin(Request $request)
    {
        abort_unless(auth()->user()->role?->name === 'master_admin', 403);
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        if ($user->role_id == 1) {
            return back()->with('error', 'Cannot demote the master admin.');
        }
        if ($user->role_id == 2) {
            return back()->with('error', 'This user is already a secondary admin.');
        }

        $user->role_id = 2;
        $user->admin_permissions = [];
        $user->save();

        return back()->with('success', "{$user->name} has been added as a secondary admin.");
    }

    public function updateSecondaryAdminPermissions(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role_id', 2)->firstOrFail();
        $user->admin_permissions = $request->input('permissions', []);
        $user->save();
        return back()->with('success', 'Permissions updated for ' . $user->name . '.');
    }

    public function removeSecondaryAdmin($id)
    {
        $user = User::where('id', $id)->where('role_id', 2)->firstOrFail();
        $user->role_id = 3;
        $user->admin_permissions = null;
        $user->save();
        return back()->with('success', "{$user->name} has been removed from secondary admin.");
    }

    public function ecommerceCustomers()
    {
        abort_unless(auth()->user()->hasAdminPermission('users'), 403);
        $users = User::with('role')
            ->where('role_id', 3)
            ->withCount([
                'customerOrders',
                'customerOrders as pending_orders_count' => fn($q) => $q->whereNotIn('status', ['delivered', 'cancelled']),
                'customerOrders as delivered_orders_count' => fn($q) => $q->where('status', 'delivered'),
            ])
            ->latest()
            ->get();
        return view('Admin.userSetting.ecommerceCustomers', compact('users'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();
        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }

    public function deleteEcommerceCustomer($id)
    {
        abort_unless(auth()->user()->hasAdminPermission('users.delete'), 403);
        $user = User::findOrFail($id);
        $user->customerOrders()->each(function ($order) {
            $order->items()->delete();
            if ($order->payment_screenshot) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_screenshot);
            }
            $order->delete();
        });
        $user->delete();
        return redirect()->route('ecommerceCustomers')->with('success', 'Customer "' . $user->name . '" and all their orders deleted successfully.');
    }

    public function adminUserOrders($id)
    {
        $user = User::findOrFail($id);
        $orders = \App\Models\CustomerOrder::where('user_id', $id)->latest()->get();
        return view('Admin.userSetting.userOrderHistory', compact('user', 'orders'));
    }

    public function getStates()
    {
        $states = [
            'Andhra Pradesh',
            'Arunachal Pradesh',
            'Assam',
            'Bihar',
            'Chhattisgarh',
            'Goa',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jharkhand',
            'Karnataka',
            'Kerala',
            'Madhya Pradesh',
            'Maharashtra',
            'Manipur',
            'Meghalaya',
            'Mizoram',
            'Nagaland',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'Sikkim',
            'Tamil Nadu',
            'Telangana',
            'Tripura',
            'Uttar Pradesh',
            'Uttarakhand',
            'West Bengal',
            'Andaman and Nicobar Islands',
            'Chandigarh',
            'Dadra and Nagar Haveli and Daman and Diu',
            'Delhi',
            'Jammu and Kashmir',
            'Ladakh',
            'Lakshadweep',
            'Puducherry'
        ];
        return $states;
    }

    public function getCities()
    {
        $cities = [
            'Ajmer',
            'Kota',
            'Ganganagar',
            'Alwar',
            'Udaipur',
            'Bikaner',
            'Jodhpur',
            'Bhilwara',
            'Sikar',
            'Tonk',
            'Chittorgarh',
            'Barmer',
            'Jaisalmer',
            'Jalor',
            'Sawai Madhopur',
            'Banswara',
            'Dungarpur',
            'Pratapgarh',
            'Rajsamand',
            'Hanumangarh',
            'Dausa',
            'Bharatpur',
            'Sri Ganganagar',
            'Nagaur',
            'Kishangarh',
            'Phalodi',
            'Mount Abu',
            'New Delhi',
            'Ghaziabad',
            'Noida',
            'Mumbai',
            'Delhi',
            'Bangalore',
            'Hyderabad',
            'Ahmedabad',
            'Chennai',
            'Kolkata',
            'Surat',
            'Pune',
            'Jaipur'
        ];
        return $cities;
    }
}
