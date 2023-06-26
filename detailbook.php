<?php
    session_start();
    require_once('./variable/variable.php');

    $var = new Variable;

    if (!isset($_SESSION['login'])) {
        header('location: login.php');
    }

    if (!isset($_GET['id'])) {
        header('location: books.php');
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
        <title>Detail Book - SimPenBuk</title>
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
                            Book Detail <a href="books.php" class="shadow-sm btn btn-primary btn-sm" style="float: right;"><i class="fa fa-arrow-left"></i> Back</a>
                            <div style="clear: both;"></div>
                        </div>
                        <form id="formUpdateBook">
                            <div class="row mt-5">
                                <div class="col-md-3">
                                    <input type="file" id="thumbnail_update" style="display: none;" accept="image/png, image/gif, image/jpeg" >
                                    <div style="overflow:hidden;width: 200px;margin: auto" class="d-flex align-items-center shadow">
                                        <img src="" class="img-responsive shadow-sm imagepp" style="width: 100%; cursor: pointer;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="title_update" class="form-label">Title</label>
                                            <input id="title_update" type="text" class="form-control shadow-sm" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="writer_update" class="form-label">Writer</label>
                                            <input id="writer_update" type="text" class="form-control shadow-sm" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="publisher_update" class="form-label">Publisher</label>
                                            <input id="publisher_update" type="text" class="form-control shadow-sm" disabled>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="stock_update" class="form-label">Stock</label>
                                            <input id="stock_update" type="text" class="form-control shadow-sm" disabled>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="note_update" class="form-label">Note</label>
                                            <textarea id="note_update" rows="4" class="form-control shadow-sm" disabled></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success shadow-sm btnUpdate"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-primary shadow-sm btnSave" style="display: none;"><i class="fa fa-save"></i></button>
                                    <button type="button" class="btn btn-danger shadow-sm btnDelete"><i class="fa fa-trash"></i></button>
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

                await fetchData()

                function fetchData() {
                    return $.ajax({
                        url: '<?= $var->url; ?>/json/showbook.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            id: '<?= $_GET['id']?>'
                        }, 
                        success: (response) => {
                            if (!response.data) {
                                let path = window.location.pathname.split('/')
                                delete path[path.length-1]

                                window.location = path.join('/')+'books.php'
                            }

                            $('#title_update').val(response.data.title)
                            $('#writer_update').val(response.data.writer)
                            $('#publisher_update').val(response.data.publisher)
                            $('#stock_update').val(response.data.stock)
                            $('#note_update').val(response.data.note)
                            $('.imagepp').attr('src', `./uploads/${response.data.thumbnail}`)
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    }); 
                }

                $(document).on('click', '.btnUpdate', function() {
                    $('#title_update').attr('disabled', false)
                    $('#writer_update').attr('disabled', false)
                    $('#publisher_update').attr('disabled', false)
                    $('#stock_update').attr('disabled', false)
                    $('#note_update').attr('disabled', false)

                    $('#title_update').focus()

                    $(this).css('display', 'none')
                    $('.btnSave').css('display', 'inline-block')
                    mode = 'update'
                })

                $('.imagepp').on('click', function() {
                    if (mode == 'update') {
                        $('#thumbnail_update').trigger('click')
                    }
                })

                $('#thumbnail_update').on('change', function() {
                    let files = $(this).prop('files')
                    $('.imagepp').attr('src', URL.createObjectURL(files[0]))
                })

                $('#formUpdateBook').on('submit', function(e) {
                    e.preventDefault()
                    mode = 'read'

                    let formData = new FormData()
                    formData.append('title', $('#title_update').val())
                    formData.append('writer', $('#writer_update').val())
                    formData.append('publisher', $('#publisher_update').val())
                    formData.append('stock', $('#stock_update').val())
                    formData.append('note', $('#note_update').val())
                    formData.append('thumbnail', $('#thumbnail_update').prop('files')[0])

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/updatebook.php?id=<?= $_GET['id']?>',
                        data: formData,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: (response) => {
                            
                            $('#title_update').attr('disabled', true)
                            $('#writer_update').attr('disabled', true)
                            $('#publisher_update').attr('disabled', true)
                            $('#stock_update').attr('disabled', true)
                            $('#note_update').attr('disabled', true)

                            $('.btnUpdate').css('display', 'inline-block')
                            $('.btnSave').css('display', 'none')

                            Toast.fire({
                                icon: 'success',
                                title: 'Book has been updated'
                            })

                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Book failed to updated'
                            })
                            fetchData()
                        }
                    })
                })

                $("#changePassword").on("hidden.bs.modal", function () {
                    $('#updateOfficer').modal('show')
                })

                $(document).on('click', '.btnDelete', function() {
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
                                url: '<?= $var->url; ?>/json/deletebook.php',
                                method: 'GET',
                                dataType: 'JSON',
                                data: {
                                    id: '<?= $_GET["id"]?>'
                                }, 
                                success: async () => {
                                    await Toast.fire({
                                        icon: 'success',
                                        title: 'Book has been deleted'
                                    })

                                    let path = window.location.pathname.split('/')
                                    delete path[path.length-1]

                                    window.location = path.join('/')+'books.php'
                                    fetchData()
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Book failed to deleted'
                                    })
                                    fetchData()
                                }
                            })
                        }
                    })
                })

                $('#formCreateBook').on('submit', function(e) {
                    e.preventDefault()
                    let data = {
                        title: $('#title_new').val(),
                        writer: $('#writer_new').val(),
                        publisher: $('#publisher_new').val(),
                        stock: $('#stock_new').val(),
                        note: $('#note_new').val()
                    }

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/createbook.php',
                        data: data,
                        dataType: 'json',
                        success: (response) => {
                            
                            $('#createBook').modal('toggle')
                            $('#title_new').val('')
                            $('#writer_new').val('')
                            $('#publisher_new').val('')
                            $('#stock_new').val('')
                            $('#note_new').val(1)

                            Toast.fire({
                                icon: 'success',
                                title: 'Book has been created'
                            })
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Book failed to created'
                            })
                            fetchData()
                        }
                    })
                })

                $('#formUpdateOfficer').on('submit', function(e) {
                    e.preventDefault()
                    let data = {
                        name: $('#name_update').val(),
                        email: $('#email_update').val(),
                        phone: $('#phone_update').val(),
                        role: $('#role_update').val(),
                        gender: $('#gender_update').val(),
                        address: $('#address_update').val(),
                    }

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/updateofficer.php?id='+idUpdate,
                        data: data,
                        dataType: 'json',
                        success: (response) => {
                            
                            $('#updateOfficer').modal('toggle')
                            $('#name_update').val('')
                            $('#email_update').val('')
                            $('#phone_update').val('')
                            $('#role_update').val(1)
                            $('#gender_update').val(0)
                            $('#address_update').val('')

                            Toast.fire({
                                icon: 'success',
                                title: 'Officer has been updated'
                            })
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Officer failed to updated'
                            })
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
                                title: 'Officer has been updated'
                            })

                            $('#changePassword').modal('toggle')
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Officer failed to updated'
                            })
                            fetchData()
                        }
                    })
                })
            })
        </script>
    </body>
</html>
