<!doctype html>
<html lang='en' class='h-100'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content='USER MANAGEMENT SYSTEM'>
    <meta name='author' content='HIDRAN ARIAS'>
    <title>USER MANAGEMENT SYSTEM</title>
    <link rel='icon' href='favicon.ico' type='image/png'>

    <link href='https://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet'>


    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css' integrity='sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==' crossorigin='anonymous' referrerpolicy='no-referrer' />


    <link href='css/style.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>


    <style>
        main>.container {
            padding: 60px 15px 0;
        }
    </style>

</head>

<body class='d-flex flex-column h-100'>

    <header>
        <!-- Fixed navbar -->
        <nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
            <div class='container-fluid'>
                <a class='navbar-brand' href='#'>
                    <i class="fa-solid fa-users fa-lg"></i>
                </a>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarCollapse' aria-controls='navbarCollapse' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarCollapse'>
                    <ul class='navbar-nav me-auto mb-2 mb-md-0'>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='#'>Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='#'>Link</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link disabled'>Disabled</a>
                        </li>
                    </ul>
                    <form class='d-flex' role='search'>
                        <input class='form-control me-2' type='search' placeholder='Search' aria-label='Search'>
                        <button class='btn btn-outline-success' type='submit'>Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <!-- Begin page content -->
    <main class='flex-shrink-0'>
        <div class='container'>
            <h1>USER MANAGEMENT SYSTEM</h1>
        </div>
    </main>

    <footer class='footer mt-auto py-3 bg-light'>
        <div class='container'>
            <span class='text-muted'>
                @copyright <?= date('d/m/Y') ?> </span>
        </div>
    </footer>


    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>



</body>

</html>