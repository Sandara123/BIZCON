<?php
// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://bizcon.com.ph/ " target="_blank">
        
        <span class="ms-1 text-sm text-dark">Bizcon Distribution Inc.</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark <?= $currentPage == 'dashboard.php' ? 'active bg-gradient-dark text-white' : '' ?>" href="../admin/dashboard.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark <?= $currentPage == 'stocks.php' ? 'active bg-gradient-dark text-white' : '' ?>" href="../admin/stocks.php">
            <i class="material-symbols-rounded opacity-5">table_view</i>
            <span class="nav-link-text ms-1">Product-Stocks</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark <?= $currentPage == 'accounts.php' ? 'active bg-gradient-dark text-white' : '' ?>" href="../admin/accounts.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Accounts</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-dark <?= $currentPage == 'settings.php' ? 'active bg-gradient-dark text-white' : '' ?>" href="../admin/settings.php">
            <i class="material-symbols-rounded opacity-5">settings</i>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
        </li>

      </ul>
    </div>
    </div>
  </aside>