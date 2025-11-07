<!-- SUPER ADMIN MENU -->
@if(auth()->user()->role === 'super_admin')
    <div class="mb-3">
        <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Super Admin</p>
    </div>
    
    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.dashboard') ? 'bg-white/20' : '' }}">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="{{ route('super-admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.users.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-users-cog w-5 text-center"></i>
        <span class="text-sm">User Management</span>
    </a>
    
    <a href="{{ route('super-admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.roles.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-shield-alt w-5 text-center"></i>
        <span class="text-sm">Roles & Permissions</span>
    </a>
    
    <a href="{{ route('super-admin.organizations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.organizations.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-building w-5 text-center"></i>
        <span class="text-sm">Organizations</span>
    </a>
    
    <a href="{{ route('super-admin.patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.patients.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-user-injured w-5 text-center"></i>
        <span class="text-sm">All Patients</span>
    </a>
    
    <a href="{{ route('super-admin.medications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.medications.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-pills w-5 text-center"></i>
        <span class="text-sm">Medications</span>
    </a>
    
    <a href="{{ route('super-admin.facilities.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.facilities.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-hospital w-5 text-center"></i>
        <span class="text-sm">Facilities</span>
    </a>
    
    <a href="{{ route('super-admin.departments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('super-admin.departments.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-sitemap w-5 text-center"></i>
        <span class="text-sm">Departments</span>
    </a>
    
    <div class="my-3 border-t border-white/10"></div>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-history w-5 text-center"></i>
        <span class="text-sm">Audit Logs</span>
        <span class="ml-auto text-xs bg-yellow-500/20 text-yellow-200 px-2 py-0.5 rounded">Soon</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-cogs w-5 text-center"></i>
        <span class="text-sm">System Settings</span>
        <span class="ml-auto text-xs bg-yellow-500/20 text-yellow-200 px-2 py-0.5 rounded">Soon</span>
    </a>

<!-- ORGANIZATION ADMIN / ADMIN MENU -->
@elseif(in_array(auth()->user()->role, ['organization_admin', 'admin']))
    <div class="mb-3">
        <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Administration</p>
    </div>
    
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.patients.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-user-injured w-5 text-center"></i>
        <span class="text-sm">Patients</span>
    </a>
    
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-users w-5 text-center"></i>
        <span class="text-sm">Users</span>
    </a>
    
    <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-user-tag w-5 text-center"></i>
        <span class="text-sm">Roles</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors">
        <i class="fas fa-chart-bar w-5 text-center"></i>
        <span class="text-sm">Reports</span>
    </a>

<!-- CLINICIAN MENU -->
@elseif(auth()->user()->role === 'clinician')
    <div class="mb-3">
        <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Clinician Portal</p>
    </div>
    
    <a href="{{ route('clinician.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('clinician.dashboard') ? 'bg-white/20' : '' }}">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="{{ route('clinician.patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('clinician.patients.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-users w-5 text-center"></i>
        <span class="text-sm">My Patients</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-heartbeat w-5 text-center"></i>
        <span class="text-sm">Vital Signs (Coming Soon)</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-pills w-5 text-center"></i>
        <span class="text-sm">Medications (Coming Soon)</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-exclamation-triangle w-5 text-center"></i>
        <span class="text-sm">Health Alerts (Coming Soon)</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
        <i class="fas fa-comments w-5 text-center"></i>
        <span class="text-sm">Messages (Coming Soon)</span>
    </a>

<!-- PATIENT MENU -->
@elseif(auth()->user()->role === 'patient')
    <div class="mb-3">
        <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Patient Portal</p>
    </div>
    
    <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('patient.dashboard') ? 'bg-white/20' : '' }}">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="{{ route('patient.profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('patient.profile.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-user-circle w-5 text-center"></i>
        <span class="text-sm">My Profile</span>
    </a>
    
    <a href="{{ route('patient.vitals.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('patient.vitals.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-heartbeat w-5 text-center"></i>
        <span class="text-sm">Vital Signs</span>
    </a>
    
    <a href="{{ route('patient.medications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('patient.medications.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-pills w-5 text-center"></i>
        <span class="text-sm">My Medications</span>
    </a>
    
    <a href="{{ route('patient.wearables.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('patient.wearables.*') ? 'bg-white/20' : '' }}">
        <i class="fas fa-watch w-5 text-center"></i>
        <span class="text-sm">Wearable Data</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors">
        <i class="fas fa-calendar-check w-5 text-center"></i>
        <span class="text-sm">Appointments</span>
    </a>

<!-- SUPPORT / MANAGER MENU -->
@elseif(in_array(auth()->user()->role, ['support', 'manager', 'health_coach']))
    <div class="mb-3">
        <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
    </div>
    
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors">
        <i class="fas fa-tasks w-5 text-center"></i>
        <span class="text-sm">My Tasks</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors">
        <i class="fas fa-ticket-alt w-5 text-center"></i>
        <span class="text-sm">Support Tickets</span>
    </a>
@endif

<!-- COMMON MENU FOR ALL ROLES -->
<div class="my-3 border-t border-white/10"></div>

<div class="mb-2">
    <p class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">Account</p>
</div>

<!-- Notifications -->
<a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('notifications.*') ? 'bg-white/20' : '' }}">
    <i class="fas fa-bell w-5 text-center"></i>
    <span class="text-sm">Notifications</span>
    @if(auth()->user()->unread_notifications_count > 0)
    <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-semibold">
        {{ auth()->user()->unread_notifications_count }}
    </span>
    @endif
</a>

<!-- My Settings -->
<a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition-colors opacity-50 cursor-not-allowed">
    <i class="fas fa-user-cog w-5 text-center"></i>
    <span class="text-sm">My Settings</span>
    <span class="ml-auto text-xs bg-yellow-500/20 text-yellow-200 px-2 py-0.5 rounded">Soon</span>
</a>

<form action="{{ route('logout') }}" method="POST" class="mt-2">
    @csrf
    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-600 transition-colors text-left">
        <i class="fas fa-sign-out-alt w-5 text-center"></i>
        <span class="text-sm">Logout</span>
    </button>
</form>
