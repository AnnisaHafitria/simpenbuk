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
        <title>Books - SimPenBuk</title>
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
                            Books Data <button class="shadow-sm btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBook" style="float: right"><i class="fa fa-plus"></i> Create New</button>
                            <a href="<?= $var->url;?>/json/exportdatabook.php" class="shadow-sm btn btn-success btn-sm" id="btnExport" style="float: right; margin-right: 10px"><i class="fa fa-file-excel"></i> Export</a>
                        </div>
                        <input type="text" class="form-control mt-4 shadow-sm" placeholder="Search" id="searchInput">
                        <div class="row bodyBook">
                            <!-- <div class="col-md-3">
                                <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                    <img src="./default-placeholder.png" class="card-img-top img-responsive" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title placeholder-glow">
                                            <span class="placeholder col-6"></span>
                                        </h5>
                                        <p class="card-text placeholder-glow">
                                            <span class="placeholder col-3"></span>
                                        </p>
                                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-3">
                                <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                    <img src="./default-placeholder.png" class="card-img-top img-responsive" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Lorem Ipsum</h5>
                                        <p class="card-text small">By Writer</p>
                                        <a href="#" class="btn btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                    <img src="./default-placeholder.png" class="card-img-top img-responsive" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Dolor Sit</h5>
                                        <p class="card-text small">By Writer</p>
                                        <a href="#" class="btn btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                    <img src="./default-placeholder.png" class="card-img-top img-responsive" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Amet Ipsum</h5>
                                        <p class="card-text small">By Writer</p>
                                        <a href="#" class="btn btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div> -->
                        </div>
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
        <!-- Modal Create Book -->
        <div class="modal fade" id="createBook" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formCreateBook">
                        <div class="modal-body p-4">
                            <input type="file" id="thumbnail_new" style="display: none;" accept="image/png, image/gif, image/jpeg" >
                            <div style="overflow:hidden;width: 200px;margin: auto" class="d-flex align-items-center shadow">
                                <img src="./uploads/default_placeholder.png" class="img-responsive shadow-sm imagepp" style="width: 100%; cursor: pointer;" alt="">
                            </div>
                            <div class="text-center mt-3 mb-5">Thumbnail</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title_new" class="form-label">Title</label>
                                        <input type="text" class="shadow-sm form-control" id="title_new">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="writer_new" class="form-label">Writer</label>
                                        <input type="text" class="shadow-sm form-control" id="writer_new">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="publisher_new" class="form-label">Publisher</label>
                                        <input type="text" class="shadow-sm form-control" id="publisher_new">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_new" class="form-label">Stock</label>
                                        <input type="number" class="shadow-sm form-control" id="stock_new">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="note_new" class="form-label">Note</label>
                                        <textarea class="form-control shadow-sm" rows="4" id="note_new"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary shadow-sm save_new_Book"><i class="fa fa-save"></i> Save</button>
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
                    let tbody = $('.bodyBook')
                    tbody.empty()
                    for (let index = 0; index < 4; index++) {
                        tbody.append(`
                            <div class="col-md-3">
                                <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                    <img src="./uploads/default_placeholder.png" class="card-img-top img-responsive" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title placeholder-glow">
                                            <span class="placeholder col-6"></span>
                                        </h5>
                                        <p class="card-text placeholder-glow">
                                            <span class="placeholder col-3"></span>
                                        </p>
                                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                                    </div>
                                </div>
                            </div>
                        `)
                    }
                    
                    return $.ajax({
                        url: '<?= $var->url; ?>/json/book.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            page: param.page,
                            search: $('#searchInput').val()
                        }, 
                        success: (response) => {
                            tbody.empty()
                            if (!response.data.length) {
                                tbody.append(`
                                    <div class="col-md-3">
                                        No Data.
                                    </div>
                                `)
                            }

                            response.data.forEach((item, index) => {
                                tbody.append(`
                                    <div class="col-md-3">
                                        <div class="card shadow-sm mb-4 mt-4" style="width: 100%; border: none; border-radius: 12px;">
                                            <img src="./uploads/${item.thumbnail}" class="card-img-top img-responsive" alt="...">
                                            <div class="card-body">
                                                <h5 class="card-title">${item.title}</h5>
                                                <p class="card-text small" style="font-style: italic">by ${item.writer}</p>
                                                <a href="detailbook.php?id=${item.id}" class="btn btn-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
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
                        url: '<?= $var->url; ?>/json/showofficer.php',
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
                        }
                    })
                })

                $('.imagepp').on('click', function() {
                    $('#thumbnail_new').trigger('click')
                })

                $('#thumbnail_new').on('change', function() {
                    let files = $(this).prop('files')
                    $('.imagepp').attr('src', URL.createObjectURL(files[0]))
                })

                $('#formCreateBook').on('submit', function(e) {
                    e.preventDefault()

                    let formData = new FormData()
                    formData.append('title', $('#title_new').val())
                    formData.append('writer', $('#writer_new').val())
                    formData.append('publisher', $('#publisher_new').val())
                    formData.append('stock', $('#stock_new').val())
                    formData.append('note', $('#note_new').val())
                    formData.append('thumbnail', $('#thumbnail_new').prop('files')[0])

                    $.ajax({
                        type: "POST",
                        url: '<?= $var->url; ?>/json/createbook.php',
                        data: formData,
                        contentType: false,
                        processData: false,
                        enctype: 'multipart/form-data',
                        success: (response) => {
                            
                            $('#createBook').modal('toggle')
                            $('#title_new').val('')
                            $('#writer_new').val('')
                            $('#publisher_new').val('')
                            $('#stock_new').val('')
                            $('#note_new').val('')
                            $('#thumbnail_new').val('')
                            $('.imagepp').attr('src', './uploads/default_placeholder.png')

                            Toast.fire({
                                icon: 'success',
                                title: 'Book has been created'
                            })
                            param.page = 1
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Book failed to created'
                            })
                            param.page = 1
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
                            param.page = 1
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Officer failed to updated'
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
                                title: 'Officer has been updated'
                            })
                            param.page = 1

                            $('#changePassword').modal('toggle')
                            fetchData()
                        },
                        error: (err) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Officer failed to updated'
                            })
                            param.page = 1
                            fetchData()
                        }
                    })
                })

                $('.nextData').click(function() {
                    param.page += 1;

                    if (param.page > param.maxPage) {
                        param.page = param.maxPage
                    }

                    fetchData();
                });

                $('.prevData').click(function() {
                    param.page -= 1;

                    if (param.page < 1) {
                        param.page = 1
                    }

                    fetchData();
                });

                $('#searchInput').on('input', debounce(function() {
                    param.page = 1
                    fetchData()
                }))
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
