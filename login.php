<?php
    session_start();
    require_once('./variable/variable.php');

    if (isset($_SESSION['login'])) {
        header('location: index.php');
    }

    if (isset($_POST['login'])) {
        $var = new Variable;
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $sql = "SELECT * FROM users where email = '{email}' and password = '{password}'";
        $sql = str_replace('{email}', $email, $sql);
        $sql = str_replace('{password}', $password, $sql);

        $check = $var->query($sql);
        $errmsg = "";
        
        if ($check->num_rows > 0) {
            $_SESSION['login'] = [
                'status' => true,
                'user' => $check->fetch_assoc()
            ];

            header('location: index.php');
        } else {
            $errmsg = "
                Toast.fire({
                    icon: 'error',
                    title: 'Email Or Password Wrong!'
                })
            ";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SimPenBuk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">SimPenBuk</h3></div>
                                    <div class="card-body">
                                        <form action="login.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" />
                                                <label for="inputEmail">Email address</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button name="login" class="btn btn-primary" href="index.php"><i class="fa fa-sign-in"></i> Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">No have account? <a href="register.php">Sign up</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">@Copyright By 20552011172 Annisa Hafitria</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            })

            <?= isset($errmsg) ? $errmsg : ''; ?>
        </script>
    </body>
</html>
