<?php
    session_start();
    require_once('./variable/variable.php');

    $var = new Variable;

    if (!isset($_SESSION['login'])) {
        header('location: login.php');
    }

    $sql = "SELECT * FROM users where id = '{id}'";
    $sql = str_replace('{id}', $_SESSION['login']['user']['id'], $sql);
    $check = $var->query($sql);

    if ($check->num_rows > 0) {
        $_SESSION['login'] = [
            'status' => true,
            'user' => $check->fetch_assoc()
        ];
    }

    $user = $_SESSION['login']['user'];
    $role = $user['role'];
    $user['role'] = $var->getRole($user['role']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Profile - SimPenBuk</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">SimPenBuk</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <!-- <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div> -->
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i> <?= $user['name']?></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <?php if ($user['role'] == 'Superadmin' || $user['role'] == 'Admin'): ?>
                                <a class="nav-link" href="books.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                    Books
                                </a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    User
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <?php if ($user['role'] == 'Superadmin'): ?>
                                            <a class="nav-link" href="officer.php">Officer</a>
                                        <?php endif; ?>
                                        <a class="nav-link" href="member.php">Member</a>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= $user['role'] ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="lead mt-4">
                            Profile <a href="books.php" class="shadow-sm btn btn-primary btn-sm" style="float: right;"><i class="fa fa-arrow-left"></i> Back</a>
                            <div style="clear: both;"></div>
                        </div>
                        <form id="formUpdateProfile">
                            <div class="row mt-5">
                                <div class="col-md-3">
                                    <input type="file" id="photo_profile_new" style="display: none;" accept="image/png, image/gif, image/jpeg" >
                                    <div style=" border-radius: 50%; overflow:hidden; height: 200px; width: 200px;margin: auto" class="d-flex align-items-center shadow">
                                        <img src="./uploads/<?= $user['picture']?>" class="img-responsive shadow-sm imagepp" style="width: 100%; cursor: pointer;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="name_update" class="form-label">Name</label>
                                            <input id="name_update" type="text" class="form-control shadow-sm" value="<?= $user['name']?>" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="email_update" class="form-label">Email</label>
                                            <input id="email_update" type="email" class="form-control shadow-sm" value="<?= $user['email']?>" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="role_update" class="form-label">Role</label>
                                            <input id="role_update" type="text" class="form-control shadow-sm" value="<?= $user['role']?>" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="gender_update" class="form-label">Gender</label>
                                            <select class="form-select shadow-sm" id="gender_update" disabled>
                                                <option value="0" <?= $user['gender'] == 0 ? 'selected' : '' ?>>Male</option>
                                                <option value="1" <?= $user['gender'] == 1 ? 'selected' : '' ?>>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="phone_update" class="form-label">Phone</label>
                                            <input id="phone_update" type="number" class="form-control shadow-sm" value="<?= $user['phone']?>" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="password_update" class="form-label">Password</label>
                                            <div>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#changePassword"><i class="fa fa-lock"></i> Change Password</button>                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="address_update" class="form-label">Address</label>
                                            <textarea id="address_update" rows="4" class="form-control shadow-sm" disabled><?= $user['address']?></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success shadow-sm btnUpdate"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-primary shadow-sm btnSave" style="display: none;"><i class="fa fa-save"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">@Copyright By 20552011172 Annisa Hafitria</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Change Password -->
        <div class="modal fade" id="changePassword" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formChangePassword">
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="password_update" class="form-label">New Password</label>
                                        <input type="password" class="shadow-sm form-control" id="password_update">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary shadow-sm save_update_password"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(async function() {
                let mode = 'read'

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true
                })

                $(document).on('click', '.btnUpdate', function() {
                    $('#name_update').attr('disabled', false)
                    $('#email_update').attr('disabled', false)
                    $('#phone_update').attr('disabled', false)
                    $('#address_update').attr('disabled', false)
                    $('#gender_update').attr('disabled', false)

                    $('#name_update').focus()
                    $(this).css('display', 'none')
                    $('.btnSave').css('display', 'inline-block')
                    mode = 'update'
                })

                $('.imagepp').on('click', function() {
                    if (mode == 'update') {
                        $('#photo_profile_new').trigger('click')
                    }
                })

                $('#photo_profile_new').on('change', function() {
                    let files = $(this).prop('files')
                    $('.imagepp').attr('src', URL.createObjectURL(files[0]))
                })

                $('#formUpdateProfile').on('submit', function(e) {
                    e.preventDefault()
                    mode = 'read'

                    let formData = new FormData()
                    formData.append('name', $('#name_update').val())
                    formData.append('email', $('#email_update').val())
                    formData.append('phone', $('#phone_update').val())
                    formData.append('address', $('#address_update').val())
                    formData.append('gender', $('#gender_update').val())
                    formData.append('picture', $('#photo_profile_new').prop('files')[0])
                    formData.append('role', '<?= $role ?>')

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/<?= $user['role'] == 'Superadmin' ? 'updateofficer.php' : 'updateprofilemember.php'?>?id=<?= $user['id']?>',
                        data: formData,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: (response) => {
                            
                            $('#name_update').attr('disabled', true)
                            $('#email_update').attr('disabled', true)
                            $('#phone_update').attr('disabled', true)
                            $('#address_update').attr('disabled', true)
                            $('#gender_update').attr('disabled', true)

                            $('.btnUpdate').css('display', 'inline-block')
                            $('.btnSave').css('display', 'none')

                            Toast.fire({
                                icon: 'success',
                                title: 'Profile has been updated'
                            })
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Profile failed to updated'
                            })
                        }
                    })
                })

                $('#formChangePassword').on('submit', function(e) {
                    e.preventDefault()
                    let data = {
                        password: $('#password_update').val(),
                    }

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/changepassword.php?id=<?= $user['id']?>',
                        data: data,
                        dataType: 'json',
                        success: (response) => {
                            
                            $('#password_update').val('')

                            Toast.fire({
                                icon: 'success',
                                title: 'Password has been updated'
                            })
                            param.page = 1

                            $('#changePassword').modal('toggle')
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Password failed to updated'
                            })
                            param.page = 1
                            fetchData()
                        }
                    })
                })
            })
        </script>
    </body>
</html>
