<?php

require_once 'view/top.php';
require_once 'view/nav-login.php';
?>
<main class='flex-shrink-0 d-flex justify-content-center align-items-start min-vh-100'>
    <div class='container'>
        <div class='row justify-content-center'>
            <div class='col-12 col-md-8 col-lg-6'>
                <!-- Tabs -->
                <ul class='nav nav-tabs justify-content-center' id='loginTab' role='tablist'>
                    <li class='nav-item' role='presentation'>
                        <button class='nav-link active' id='login-tab' data-bs-toggle='tab'
                                data-bs-target='#login-tab-pane'
                                type='button' role='tab' aria-controls='login-tab-pane' aria-selected='true'>
                            LOGIN
                        </button>
                    </li>
                    <li class='nav-item' role='presentation'>
                        <button class='nav-link' id='signup-tab' data-bs-toggle='tab' data-bs-target='#signup-tab-pane'
                                type='button' role='tab' aria-controls='signup-tab-pane' aria-selected='false'>
                            SIGNUP
                        </button>
                    </li>
                </ul>

                <div class='tab-content' id='myTabContent'>
                    <!-- LOGIN -->
                    <div class='tab-pane fade show active' id='login-tab-pane' role='tabpanel'
                         aria-labelledby='login-tab' tabindex='0'>
                        <div class='card shadow-sm auth-card'>
                            <div class='card-body p-4'>
                                <h1 class='h4 mb-3 text-center'>Accesso</h1>
                                <form action='controller/login.php' method='post' novalidate>
                                    <div class='mb-3'>
                                        <label for='email' class='form-label'>Email address</label>
                                        <input type='email' class='form-control' id='email' name='email'
                                               aria-describedby='emailHelp' required>
                                        <div id='emailHelp' class='form-text'>We'll never share your email with anyone
                                            else.
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid email.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                               required minlength="6">
                                        <div class="invalid-feedback">Password is required (min 6 chars).</div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" value='1' class="form-check-input" id="remember"
                                               name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Sign in</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- SIGNUP -->
                    <div class="tab-pane fade" id="signup-tab-pane" role="tabpanel" aria-labelledby="signup-tab"
                         tabindex="0">
                        <div class="card shadow-sm auth-card">
                            <div class="card-body p-4">
                                <h2 class="h4 mb-3 text-center">Registrazione</h2>
                                <form action="controller/signup.php" method="post" novalidate>
                                    <div class="mb-3">
                                        <label for="s_email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="s_email" name="email"
                                               aria-describedby="s_emailHelp" required>
                                        <div id="s_emailHelp" class="form-text">We'll never share your email with anyone
                                            else.
                                        </div>
                                        <div class='invalid-feedback'>Please enter a valid email.</div>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='s_password' class='form-label'>Password</label>
                                        <input type='password' class='form-control' name='password' id='s_password'
                                               required minlength='6'>
                                        <div class='invalid-feedback'>Password is required (min 6 chars).</div>
                                    </div>
                                    <div class='mb-3 form-check'>
                                        <input type='checkbox' class='form-check-input' value="1" id='s_remember'
                                               name='remember'>
                                        <label class='form-check-label' for='s_remember'>Remember me</label>
                                    </div>
                                    <button type='submit' class='btn btn-success w-100'>Create account</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- /tab-content -->
            </div>
        </div>
    </div>
</main>

<?php
require_once 'view/footer.php';
?>
