<link rel="stylesheet" href="{{ asset('css/staff-responsive.css') }}">

<button id="sidebar-toggle" onclick="toggleSidebar()">&#9776;</button>
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<header class="admin-nav" id="main-sidebar">

    <div style="margin-bottom: 8px;">
        <h2 style="margin: 0 0 12px 0;">Ragadio Plaza Hotel</h2>
        <span style="font-size: 0.7rem; background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 4px; letter-spacing: 1px; opacity: 0.8;">ADMIN PORTAL</span>
    </div>

    @if(session('staff_name'))
    <div style="font-size: 0.78rem; color: #94a3b8; margin-bottom: 28px; padding-left: 2px;">
        Admin: {{ session('staff_name') }}
    </div>
    @endif

    <nav class="admin-menu">
        <a href="/staff/dashboard"    class="{{ Request::is('staff/dashboard')    ? 'active' : '' }}">Overview</a>
        <a href="/staff/rooms"        class="{{ Request::is('staff/rooms*') || Request::is('staff/add-room*') || Request::is('staff/edit-room*') ? 'active' : '' }}">Rooms</a>
        <a href="/staff/messages"     class="{{ Request::is('staff/messages')     ? 'active' : '' }}">Messages</a>
        <a href="/staff/reservations" class="{{ Request::is('staff/reservations') ? 'active' : '' }}">Reservations</a>
        <a href="/staff/housekeeping" class="{{ Request::is('staff/housekeeping') ? 'active' : '' }}">Housekeeping</a>
        <a href="/staff/services"     class="{{ Request::is('staff/services')     ? 'active' : '' }}">Services</a>
        <a href="/staff/transactions" class="{{ Request::is('staff/transactions') ? 'active' : '' }}">History</a>
        <a href="/logout" class="btn-logout">LOGOUT</a>
    </nav>

</header>
<script>
function toggleSidebar() {
    document.getElementById('main-sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('active');
}
function closeSidebar() {
    document.getElementById('main-sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('active');
}
</script>