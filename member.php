<?php
    session_start();
    require_once('./variable/variable.php');

    $var = new Variable;

    if (!isset($_SESSION['login'])) {
        header('location: login.php');
    }

    $user = $_SESSION['login']['user'];
    $user['role'] = $var->getRole($user['role']);

    if ($user['role'] != 'Superadmin' && $user['role'] != 'Admin') {
        header('location: index.php');
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
        <title>Member - SimPenBuk</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            td {
                vertical-align: middle;
            }
        </style>
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
                            Members Data 
                            <button class="shadow-sm btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMember" style="float: right"><i class="fa fa-plus"></i> Create New</button>&nbsp;
                            <a href="<?= $var->url;?>/json/exportdatamember.php" class="shadow-sm btn btn-success btn-sm" id="btnExport" style="float: right; margin-right: 10px"><i class="fa fa-file-excel"></i> Export</a>
                        </div>
                        <input type="text" class="form-control mt-4 shadow-sm" id="searchInput" placeholder="Search">
                        <table class="table table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Name</th>
                                  <th scope="col">Email</th>
                                  <th scope="col">Phone</th>
                                  <th scope="col">Address</th>
                                  <th scope="col">Gender</th>
                                  <th scope="col">Role</th>
                                  <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody"></tbody>
                        </table>
                        <nav aria-label="Page navigation example" style="float: right;">
                            <ul class="pagination">
                                <li class="page-item prevData" style="display: none;">
                                    <a class="page-link" href="javascript:void(0)">
                                        <span aria-hidden="true">Prev Data</span>
                                    </a>
                                </li>
                                <li class="page-item nextData">
                                    <a class="page-link" href="javascript:void(0)">
                                        <span aria-hidden="true">Next Data</span>
                                    </a>
                                </li>
                            </ul>
                            <div style="clear: both;"></div>
                        </nav>
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
        <!-- Modal Create Member -->
        <div class="modal fade" id="createMember" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formCreateMember">
                        <div class="modal-body p-4">
                            <input type="file" id="photo_profile_new" style="display: none;" accept="image/png, image/gif, image/jpeg" >
                            <div style=" border-radius: 50%; overflow:hidden; height: 150px; width: 150px;margin: auto" class="d-flex align-items-center shadow">
                                <img src="./uploads/avatar_default.png" class="img-responsive shadow-sm imagepp" style="width: 100%; cursor: pointer;" alt="">
                            </div>
                            <div class="text-center mt-4 mb-4">Profile Picture</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_new" class="form-label">Name</label>
                                        <input type="text" class="shadow-sm form-control" id="name_new">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_new" class="form-label">Email Address</label>
                                        <input type="email" class="shadow-sm form-control" id="email_new">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_new" class="form-label">Password</label>
                                        <input type="password" class="shadow-sm form-control" id="password_new">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_new" class="form-label">Phone</label>
                                        <input type="number" class="shadow-sm form-control" id="phone_new">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role_new" class="form-label">Role</label>
                                        <select class="form-select shadow-sm" id="role_new" disabled>
                                            <option value="3">Member</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender_new" class="form-label">Gender</label>
                                        <select class="form-select shadow-sm" id="gender_new">
                                            <option value="0">Male</option>
                                            <option value="1">Female</option>
                                        </select>    
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address_new" class="form-label">Address</label>
                                        <textarea class="form-control shadow-sm" rows="4" id="address_new"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary shadow-sm save_new_member"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Update Member -->
        <div class="modal fade" id="updateMember" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUpdateMember">
                        <div class="modal-body p-4">
                            <input type="file" id="photo_profile_update" style="display: none;" accept="image/png, image/gif, image/jpeg" >
                            <div style=" border-radius: 50%; overflow:hidden; height: 150px; width: 150px;margin: auto" class="d-flex align-items-center shadow">
                                <img src="./uploads/avatar_default.png" class="img-responsive shadow-sm imagepp-update" style="width: 100%; cursor: pointer;" alt="">
                            </div>
                            <div class="text-center mt-4 mb-4">Profile Picture</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_update" class="form-label">Name</label>
                                        <input type="text" class="shadow-sm form-control" id="name_update">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_update" class="form-label">Email Address</label>
                                        <input type="email" class="shadow-sm form-control" id="email_update">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#changePassword" class="btn btn-danger shadow"><i class="fa fa-lock"></i> Change Password</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_update" class="form-label">Phone</label>
                                        <input type="number" class="shadow-sm form-control" id="phone_update">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role_update" class="form-label">Role</label>
                                        <select class="form-select shadow-sm" id="role_update" disabled>
                                            <option value="3">Member</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender_update" class="form-label">Gender</label>
                                        <select class="form-select shadow-sm" id="gender_update">
                                            <option value="0">Male</option>
                                            <option value="1">Female</option>
                                        </select>    
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address_update" class="form-label">Address</label>
                                        <textarea class="form-control shadow-sm" rows="4" id="address_update"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary shadow-sm save_update_member"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
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
                let param = {
                    page: 1,
                    maxPage: 1
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                })

                await fetchData()

                function fetchData() {
                    let tbody = $('.tbody')
                    tbody.empty()
                    tbody.append(`
                        <tr>
                            <td colspan="8">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `)
                    return $.ajax({
                        url: '<?= $var->url; ?>/json/member.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            page: param.page,
                            search: $('#searchInput').val()
                        }, 
                        success: (response) => {
                            $('.tbody').empty()
                            if (!response.data.length) {
                                $('.tbody').append(`
                                    <tr>
                                        <td colspan='8' style='text-align: center'>No Data.</td>
                                    </tr>
                                `)
                            }

                            response.data.forEach((item, index) => {
                                $('.tbody').append(`
                                    <tr>
                                        <td>${(index+1) + ((response.meta.page-1) * 10)}</td>
                                        <td>
                                            <img src="./uploads/${item.picture}" alt="Avatar" style="vertical-align: middle; width: 25px; height: 25px; border-radius: 50%; object-fit: cover;">
                                            ${item.name}
                                        </td>
                                        <td>${item.email}</td>
                                        <td>${item.phone}</td>
                                        <td>${item.address}</td>
                                        <td><span class="badge ${item.gender == 'Male' ? 'bg-dark text-light' : 'bg-light text-dark'} shadow-sm">${item.gender}</span></td>
                                        <td><span class="badge ${item.role == 'Member' ? 'bg-success' : 'bg-warning'} shadow-sm">${item.role}</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success shadow-sm btnUpdate" data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#updateMember"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-sm btn-danger shadow-sm btnDelete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                `)
                            })
                            param.maxPage = response.meta.maxPage == 0 ? 1 : response.meta.maxPage
                            param.page = response.meta.page

                            if (param.page == 1) {
                                $('.prevData').css('display', 'none');
                            }

                            if (param.page < param.maxPage) {
                                $('.nextData').css('display', 'inline-block');
                            }

                            if (param.page > 1) {
                                $('.prevData').css('display', 'inline-block');
                            }

                            if (param.page == param.maxPage) {
                                $('.nextData').css('display', 'none');
                            }
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    }); 
                }

                let idUpdate;
                $(document).on('click', '.btnUpdate', function() {
                    let id = $(this).data('id')
                    idUpdate = id
                    $.ajax({
                        url: '<?= $var->url; ?>/json/showmember.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: (response) => {
                            $('#name_update').val(response.data.name)
                            $('#email_update').val(response.data.email)
                            $('#phone_update').val(response.data.phone)
                            $('#role_update').val(response.data.role)
                            $('#gender_update').val(response.data.gender)
                            $('#address_update').val(response.data.address)
                            $('.imagepp-update').attr('src', `./uploads/${response.data.picture}`)
                        }
                    })
                })

                $("#changePassword").on("hidden.bs.modal", function () {
                    $('#updateMember').modal('show')
                })

                $('.imagepp').on('click', function() {
                    $('#photo_profile_new').trigger('click')
                })

                $('.imagepp-update').on('click', function() {
                    $('#photo_profile_update').trigger('click')
                })

                $('#photo_profile_new').on('change', function() {
                    let files = $(this).prop('files')
                    $('.imagepp').attr('src', URL.createObjectURL(files[0]))
                })

                $('#photo_profile_update').on('change', function() {
                    let files = $(this).prop('files')
                    $('.imagepp-update').attr('src', URL.createObjectURL(files[0]))
                })

                $('#searchInput').on('input', debounce(function() {
                    param.page = 1
                    fetchData()
                }))

                $(document).on('click', '.btnDelete', function() {
                    let id = $(this).data('id')
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Delete'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $var->url; ?>/json/deletemember.php',
                                method: 'GET',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                }, 
                                success: () => {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Member has been deleted'
                                    })
                                    param.page = 1
                                    fetchData()
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Member failed to deleted'
                                    })
                                    param.page = 1
                                    fetchData()
                                }
                            })
                        }
                    })
                })

                $('#formCreateMember').on('submit', function(e) {
                    e.preventDefault()

                    let formData = new FormData()
                    formData.append('name', $('#name_new').val())
                    formData.append('email', $('#email_new').val())
                    formData.append('password', $('#password_new').val())
                    formData.append('phone', $('#phone_new').val())
                    formData.append('address', $('#address_new').val())
                    formData.append('gender', $('#gender_new').val())
                    formData.append('picture', $('#photo_profile_new').prop('files')[0])
                    formData.append('role', $('#role_new').val())

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/createmember.php',
                        data: formData,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: (response) => {
                            
                            $('#createMember').modal('toggle')
                            $('#name_new').val('')
                            $('#email_new').val('')
                            $('#password_new').val('')
                            $('#phone_new').val('')
                            $('#role_new option[value="1"]').prop('selected', true)
                            $('#gender_new').val(0)
                            $('#address_new').val('')
                            $('#photo_profile_new').val('')
                            $('.imagepp').attr('src', './uploads/avatar_default.png')

                            Toast.fire({
                                icon: 'success',
                                title: 'Member has been created'
                            })
                            param.page = 1
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Member failed to created'
                            })
                            param.page = 1
                            fetchData()
                        }
                    })
                })

                $('#formUpdateMember').on('submit', function(e) {
                    e.preventDefault()
                    let formData = new FormData()
                    formData.append('name', $('#name_update').val())
                    formData.append('email', $('#email_update').val())
                    formData.append('phone', $('#phone_update').val())
                    formData.append('address', $('#address_update').val())
                    formData.append('gender', $('#gender_update').val())
                    formData.append('picture', $('#photo_profile_update').prop('files')[0])
                    formData.append('role', $('#role_update').val())

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/updatemember.php?id='+idUpdate,
                        data: formData,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: (response) => {
                            
                            $('#updateMember').modal('toggle')
                            $('#name_update').val('')
                            $('#email_update').val('')
                            $('#phone_update').val('')
                            $('#role_update').val(1)
                            $('#gender_update').val(0)
                            $('#address_update').val('')

                            Toast.fire({
                                icon: 'success',
                                title: 'Member has been updated'
                            })
                            param.page = 1
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Member failed to updated'
                            })
                            param.page = 1
                            fetchData()
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
                        url: '<?= $var->url; ?>/json/changepassword.php?id='+idUpdate,
                        data: data,
                        dataType: 'json',
                        success: (response) => {
                            
                            $('#password_update').val('')

                            Toast.fire({
                                icon: 'success',
                                title: 'Member has been updated'
                            })
                            param.page = 1

                            $('#changePassword').modal('toggle')
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Member failed to updated'
                            })
                            param.page = 1
                            fetchData()
                        }
                    })
                })

                
                
            })

            function debounce(func, delay = 250) {
                let timerId;
                return (...args) => {
                    clearTimeout(timerId);
                    timerId = setTimeout(() => {
                        func.apply(this, args);
                    }, delay);
                };
            }
        </script>
    </body>
</html>
