<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard/' . $this->session->userdata('role')); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Maroc PME</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('dashboard/' . $this->session->userdata('role')); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Clients -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'clients') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('clients') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Clients</span>
        </a>
    </li>

    <!-- Nav Item - Users -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'user_management') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('user_management') ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Nav Item - Admins -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'admins') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admins') ?>">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Admins</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>