<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAdminPermission('banners'), 403);
        $banners    = Banner::orderBy('sort_order')->get();
        $categories = ProductCategory::where('active_status', 1)->orderBy('category_name')->get();
        return view('Admin.banners.index', compact('banners', 'categories'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAdminPermission('banners.add'), 403);
        $request->validate([
            'image'       => 'required|image|max:2048',
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image'       => $path,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Banner added successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        abort_unless(auth()->user()->hasAdminPermission('banners.edit'), 403);
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image'       => $banner->image,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Banner updated successfully.');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Banner status updated.');
    }

    public function destroy(Banner $banner)
    {
        abort_unless(auth()->user()->hasAdminPermission('banners.remove'), 403);
        Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }
}
