<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= admin_url() ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url(); ?>assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= admin_url() ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= base_url(); ?>assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <?php 
                $menuItems = get_sidebar_menu();
                foreach ($menuItems as $sectionKey => $section): 
                ?>
                    <li class="menu-title"><span data-key="t-<?= $sectionKey ?>"><?= $section['title'] ?></span></li>
                    
                    <?php foreach ($section['items'] as $itemKey => $item): ?>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= $item['url'] ?>">
                                <i class="<?= $item['icon'] ?>"></i> 
                                <span data-key="t-<?= $itemKey ?>"><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
