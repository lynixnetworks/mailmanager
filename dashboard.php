<?php
require_once 'config.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Manager - Email Administration System</title>
    <meta name="description" content="Email administration system for managing domains, email accounts, and aliases." />
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-envelope"></i> Mail Manager
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div id="alertContainer" class="position-fixed top-0 start-50 translate-middle-x mt-5" style="z-index: 1050;"></div>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <div class="list-group">
                    <a href="#aliases" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                        <i class="fas fa-forward me-2"></i> Aliases
                    </a>
                    <a href="#domains" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-globe me-2"></i> Domains
                    </a>
                    <a href="#emails" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-envelope me-2"></i> E-Mail Manager
                    </a>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="stat-item">
                                    <h4 id="domainsCount" class="text-primary">0</h4>
                                    <small class="text-muted">Domains</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stat-item">
                                    <h4 id="emailsCount" class="text-success">0</h4>
                                    <small class="text-muted">Emails</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h4 id="aliasesCount" class="text-warning">0</h4>
                                    <small class="text-muted">Aliases</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h4 id="totalCount" class="text-info">0</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="tab-content">
                     <div class="tab-pane fade show active" id="aliases">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="mb-1"><i class="fas fa-forward text-primary me-2"></i>Manage Aliases</h4>
                                <p class="text-muted mb-0">Manage email forwarding and aliases</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAliasModal">
                                <i class="fas fa-plus me-1"></i> Add Alias
                            </button>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Aliases List</h6>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted" id="aliasesSummary">Loading...</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="aliasesTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="80">ID</th>
                                                <th>Domain</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th width="120" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 mb-0">Loading aliases...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="tab-pane fade" id="domains">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="mb-1"><i class="fas fa-globe text-success me-2"></i>Manage Domains</h4>
                                <p class="text-muted mb-0">Manage email domains</p>
                            </div>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDomainModal">
                                <i class="fas fa-plus me-1"></i> Add Domain
                            </button>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Domains List</h6>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted" id="domainsSummary">Loading...</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="domainsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="80">ID</th>
                                                <th>Domain Name</th>
                                                <th width="120" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="text-center py-4">
                                                    <div class="spinner-border text-success" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 mb-0">Loading domains...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="tab-pane fade" id="emails">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="mb-1"><i class="fas fa-envelope text-info me-2"></i>Manage Email Accounts</h4>
                                <p class="text-muted mb-0">Manage user email accounts</p>
                            </div>
                            <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                                <i class="fas fa-plus me-1"></i> Add Email
                            </button>
                        </div>
                        
                         <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters & Search</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="domainFilter" class="form-label">Filter by Domain</label>
                                        <select class="form-select" id="domainFilter">
                                            <option value="">All Domains</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="searchEmail" class="form-label">Search Email</label>
                                        <input type="text" class="form-control" id="searchEmail" placeholder="Enter email address...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                                                <i class="fas fa-refresh me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Email Accounts</h6>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted" id="emailsSummary">Loading...</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="emailsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="80">ID</th>
                                                <th>Domain</th>
                                                <th>Email Address</th>
                                                <th>Password Hash</th>
                                                <th width="120" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="spinner-border text-info" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 mb-0">Loading email accounts...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <?php include 'modals.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>