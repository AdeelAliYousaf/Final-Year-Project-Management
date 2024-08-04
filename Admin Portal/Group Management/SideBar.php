<head>
  <style>
    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
      padding: 48px 0 0;
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
      width: 250px; /* Initial width */
      transition: width 0.3s ease;
    }

    .sidebar-sticky {
      position: relative;
      top: 0;
      height: calc(100vh - 48px);
      padding-top: .5rem;
      overflow-x: hidden;
      overflow-y: auto;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 0;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <span class="navbar-brand">Group Management</span>
    </div>
  </nav>

  <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Admin Dashboard</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="../AdminDashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px;" href="../Student Management/main.php">Manage Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px;" href="../Advisor Management/main.php">Manage Advisors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px;" href="main.php">Manage Groups</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px;" href="../Project Management/main.php">Manage Projects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px;" href="../Group Project Management/main.php">Manage Group Projects</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="padding-top:30px; color:red;" href="../Logout/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</body>