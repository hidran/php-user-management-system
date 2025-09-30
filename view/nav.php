<?php

$currentUrl = $_SERVER["PHP_SELF"];
$indexPage = "index.php";
$action = $_GET["action"] ?? "";
$indexActive = !$action ? "active" : "";
$newActive = $action === "insert" ? "active" : "";
$orderByColumns = $orderByColumns ?? [];
$orderBy = $orderBy ?? 'ASC';
$currentOrderDir = $currentOrderDir ?? 'ASC';
?>
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark p-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fa-solid fa-user fa-lg"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <?php
                if (is_user_logged_in()): ?>
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $indexActive ?>" aria-current="page" href="<?= $indexPage ?>"><i
                                        class="fa-solid fa-users"></i>Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?= $newActive ?>" href="<?= $indexPage ?>?action=insert"><i
                                        class="fa-solid fa-user-plus"></i>New User</a>
                        </li>

                    </ul>

                    <form method="GET" role="search" name="searchForm" id="searchForm">
                        <div class="row">
                            <div class="col-2">
                                <!-- <div class="row d-flex justify-content-center  align-content-center">-->
                                <div class='row g-2 align-items-center'>
                                    <div class="col-md-6">
                                        <label class="form-label  text-bg-dark mt-2" for="orderBy"> Order by</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" name="orderBy" id="orderBy"
                                                onchange="document.forms.searchForm.submit()">
                                            <option value="">SELECT</option>
                                            <?php
                                            foreach ($orderByColumns as $col) {
                                                $selected = $col === $orderBy ? 'selected' : '';
                                                echo "<option $selected  value ='$col'>$col</option> \n";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="row d-flex align-content-center">
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label  text-bg-dark" for="orderDir"> Dir</label>

                                    </div>
                                    <div class="col-md-8">
                                        <select class="form-select" name="orderDir" id="orderDir"
                                                onchange="document.forms.searchForm.submit()">
                                            <option value="">SELECT</option>
                                            <option value="ASC" <?= $currentOrderDir === 'ASC' ? 'selected' : '' ?>>ASC
                                            </option>
                                            <option value="DESC" <?= $currentOrderDir === 'DESC' ? 'selected' : '' ?>>
                                                DESC
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col mt-2">
                                <label class="form-label text-bg-dark" for="recordsPerPage">Records</label>
                            </div>
                            <div class="col">
                                <select class="form-select" name="recordsPerPage" id="recordsPerPage"
                                        onchange="document.forms.searchForm.submit()">
                                    <option value="">SELECT</option>
                                    <?php
                                    $recordsPerPageOptions = $recordsPerPageOptions ?? [];
                                    $recordsPerPage = $recordsPerPage ?? 10;
                                    $search = $search ?? '';
                                    $page = $page ?? 1;
                                    foreach ($recordsPerPageOptions as $v) {
                                        $v = (int)$v;
                                        $selected = $v === $recordsPerPage ? 'selected' : '';
                                        echo "<option $selected  value ='$v'>$v</option> \n";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <input name="search" value="<?= $search ?>" class="form-control me-2" type="search"
                                       placeholder="Search" aria-label="Search">
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </div>
                            <div class="col">
                                <button onclick="location.href='<?= $page ?>' " class="btn btn-outline-info"
                                        type="button">
                                    RESET
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php
                    $username = get_user_login_data()['username'];
                    ?>
                    <!--<ul class='navbar-nav me-auto mb-2 mb-navbar'>-->
                    <ul class='navbar-nav ms-auto mb-2 mb-md-0'>
                        <li class="nav-item dropdown">
                            <a id="userMenu" href='#' data-bs-toggle="dropdown" aria-expanded="false" role="button"
                               class="nav-link dropdown-toggle"><?= $username ?></a>
                            <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userMenu">
                                <li>
                                    <a role="button" class='btn btn-outline-success btn-sm w-100'
                                       href='/profile.php'>Profile</a>
                                </li>
                                <li>
                                    <hr class='dropdown-divider'>
                                </li>
                                <li>
                                    <form action="controller/logout.php" method="post">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-outline-danger btn-sm w-100" type="submit">Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>

                        </li>
                    </ul>
                <?php
                endif;
                ?>
            </div>
        </div>
    </nav>
</header>
