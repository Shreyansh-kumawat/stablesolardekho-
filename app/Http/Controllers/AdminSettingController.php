<?php

namespace App\Http\Controllers;

use App\Models\InstallationStory;
use App\Models\SolarTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function manageTeam()
    {
        $stateController = new UserController();
        $states = $stateController->getStates();
        $districts = $stateController->getCities();

        $positions = ['Channel Partner', 'Installation Partner', 'Manager', 'Project Manager', 'Lead Engineer', 'Sales Manager', 'Marketing Manager', 'Customer Support'];
        $solarTeam = SolarTeam::all();
        return view('Admin.AdminSetting.manageTeam', compact('solarTeam', 'positions', 'states', 'districts'));
    }

    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        $teamMember = new SolarTeam();
        $teamMember->name = $request->input('name');
        $teamMember->position = $request->input('position');
        $teamMember->position = $request->input('position');
        $teamMember->mobile_number = $request->input('mobile_number');
        $teamMember->address = $request->input('address');
        $teamMember->state = $request->input('state_id');
        $teamMember->district = $request->input('district_id');


        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profilePhotos', 'public');
            $teamMember->profile_photo = $photoPath;
        }

        $teamMember->save();

        return redirect()->route('manageTeam')->with('success', 'Team member added successfully.');
    }

    public function updateTeam(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:solar_teams,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        $teamMember = SolarTeam::findOrFail($request->input('id'));
        $teamMember->name = $request->input('name');
        $teamMember->position = $request->input('position');
        $teamMember->mobile_number = $request->input('mobile_number');
        $teamMember->address = $request->input('address');
        $teamMember->state = $request->input('state_id');
        $teamMember->district = $request->input('district_id');

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($teamMember->profile_photo) {
                Storage::disk('public')->delete($teamMember->profile_photo);
            }
            // Store new photo
            $photoPath = $request->file('profile_photo')->store('profilePhotos', 'public');
            $teamMember->profile_photo = $photoPath;
        }

        $teamMember->save();

        return redirect()->route('manageTeam')->with('success', 'Team member updated successfully.');
    }

    public function newInstallationStory()
    {
        return view('Admin.AdminSetting.newInstallationStory');
    }

    public function storeStory(Request $request)
    {

        $request->validate([
            'installation_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            $newStory = new InstallationStory();
            $newStory->installation_type = $request->input('installation_type');
            $newStory->location = $request->input('location');
            $newStory->system_size_kw = $request->input('system_size_kw');
            $newStory->installation_date = $request->input('installation_date');
            $newStory->videos = $request->input('video_url');

            $photos = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = date('Ymd') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('installationStories', $fileName, 'public');
                    $photos[] = $imagePath;
                }
            }

            $newStory->photos = json_encode($photos);
            $newStory->save();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while saving the story: ' . $e->getMessage());
        }

        $newStory->save();
        return redirect()->route('newInstallationStory')->with('success', 'Installation story added successfully.');
    }

        public function show($id)
    {
        $story = InstallationStory::findOrFail($id);
        return response()->json($story);
    }
        public function update(Request $request, $id)
    {
        
        $story = InstallationStory::findOrFail($id);
        $story->installation_type = $request->input('installation_type');
        $story->location = $request->input('location');
        $story->system_size_kw = $request->input('system_size_kw');
        $story->installation_date = $request->input('installation_date');
        $story->videos = $request->input('video_url');
        if($request->hasFile('images')) {
            $photos = [];
            foreach ($request->file('images') as $image) {
                $fileName = date('Ymd') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('installationStories', $fileName, 'public');
                $photos[] = $imagePath;
            }
            $story->photos = json_encode($photos);
        }
        $story->update();
        return redirect()->route('listStories')->with('success', 'Installation story updated successfully.');
    }

    public function listStories() {
        $listStories = InstallationStory::all();
        return view('Admin.AdminSetting.listStories', compact('listStories'));
    }
}
