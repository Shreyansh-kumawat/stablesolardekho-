<?php

namespace App\Http\Controllers;

use App\Mail\CpRegistrationEmail;
use App\Models\ChannelPartner;
use App\Models\ChannelPartnerRole;
use App\Models\InstallationStory;
use App\Models\InstallationVideo;
use App\Models\MyTeam;
use App\Models\OurInstallation;
use App\Models\Role;
use App\Models\SolarTeam;
use App\Models\User;
use App\Models\UserQuery;
use App\Models\UserRole;
use App\Models\UserSolarQuotation;
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
        if (Auth::check()) {
            if(Auth::user()->role_id != 1){
                $cp_type = ChannelPartner::where('id', Auth::user()->cp_id)->value('cp_role');
                if($cp_type == 1){
                }
                elseif($cp_type == 2){
                    $cpData = ChannelPartner::with('wallet')->where('id', Auth::user()->cp_id)->first();
                    return view('channelPartner.channelPartnerDashboard',compact('cp_type'))
                    ->with('cpData', $cpData);
                }
                elseif($cp_type == 3){
                }
            }
            return view( 'Admin.adminDashboard');
        }
        // $videos = InstallationVideo::active()->ordered()->get();
        $installations = InstallationStory::where('active_status', 1)
        ->orderBy('created_at', 'desc')->select('videos','photos')
        ->get();
        return view('dashboard.index')->with('installations', $installations);
    }

    public function masterAdminDashboard()
    {

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

    public function getStates()
    {
        $states =[
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
