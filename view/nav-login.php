<?php

$currentUrl = $_SERVER["PHP_SELF"];
$indexPage = "index.php";
$action = $_GET["action"] ?? "";
$indexActive = !$action ? "active" : "";
$newActive = $action === "insert" ? "active" : "";
?>
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fa-solid fa-user fa-lg"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">

            </div>
        </div>
    </nav>
</header>
