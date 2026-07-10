@extends('layouts.adminLayout')
@section('css')
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('stable/css/datatableListCss.css') }}">
<link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3.545a1.5 1.5 0 01-1.5-1.5V5.455c0-.82.67-1.5 1.5-1.5h15.91c.83 0 1.5.67 1.5 1.5v12.045M9 12h6m-3 3v3"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">User Management</h1>
                        <p class="text-sm text-slate-600">Manage system users and their roles</p>
                    </div>
                </div>
                <a href="{{ route('addNewUser') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="p-6">
                <!-- Controls Section -->
                <div class="controls-top">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Show entries:</label>
                        <select id="userTable_length" class="rounded border-slate-300">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                    </div>
                    <div id="buttons_export" style="flex: 1;"></div>
                    <div style="flex: 1;">
                        <input type="text" id="userTable_filter_input" placeholder="Search users..." class="w-full rounded border-slate-300">
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-hover dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email ID</th>
                                <th>Mobile Number</th>
                                <th>User Role</th>
                                <th>Associate With</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile_number }}</td>
                                <td>{{ $user->role ? $user->role->name : 'N/A' }}</td>
                                <td>{{ $user->channelPartner ? $user->channelPartner->cp_name : 'N/A' }}</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('edit_user', ['id' => $user->id]) }}" class="btn btn-sm btn-primary-theme d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-pencil-square"></i><span>Edit</span>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-secondary-theme d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-arrow-counterclockwise"></i><span>Reset</span>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger-theme d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-trash3"></i><span>Delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900">Tips</h4>
                    <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside mt-1">
                        <li>Use search box to quickly find users by name, email, or mobile</li>
                        <li>Export user data in Excel or CSV format</li>
                        <li>You can reset user password from the action menu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- jQuery MUST be loaded FIRST -->

@endsection
