<?php
    session_start();
    require_once('./variable/variable.php');
    require_once('./model/user.php');

    if (isset($_SESSION['login'])) {
        header('location: index.php');
    }

    if (isset($_POST['register'])) {
        $var = new Variable;
        $user = new User;
        $data = [
            [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => md5($_POST['password']),
                'phone' => $_POST['phone'],
                'picture' => 'avatar_default.png',
                'address' => $_POST['address'],
                'gender' => $_POST['gender'],
                'role' => 3
            ]
        ];

        $sql = $user->create($data);

        $check = $var->query($sql);
        $errmsg = "";

        if ($var->connection()->affected_rows == 0) {
            $errmsg = "
                Toast.fire({
                    icon: 'error',
                    title: 'Register Failed'
                })
            ";
        } else {
            $errmsg = "
                Toast.fire({
                    icon: 'success',
                    title: 'Register Success'
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
        <title>Register - SimPenBuk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Register SimPenBuk</h3></div>
                                    <div class="card-body">
                                        <form action="register.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="name" id="inputName" type="text" placeholder="Name" />
                                                        <label for="inputName">Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="email" id="inputEmail" type="email" placeholder="Email" />
                                                        <label for="inputEmail">Email address</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" />
                                                        <label for="inputPassword">Password</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="phone" id="inputPhone" type="number" placeholder="Phone" />
                                                        <label for="inputPhone">Phone</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <select id="inputGender" class="form-select" name="gender" aria-label="Default select example">
                                                            <option value="0">Male</option>
                                                            <option value="1">Female</option>
                                                        </select>
                                                        <label for="inputGender">Gender</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <textarea rows="4" name="address" class="form-control" id="inputAddress"></textarea>
                                                        <label for="inputAddress">Address</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                    <button name="register" class="btn btn-primary" href="index.php"><i class="fa fa-sign-in"></i> Sign Up</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">Have account? <a href="login.php">Login</a></div>
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
