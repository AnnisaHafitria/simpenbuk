<?php
    session_start();
    require_once('./variable/variable.php');
    require_once('./vendor/autoload.php');

    use Carbon\Carbon;

    $var = new Variable;

    if (!isset($_SESSION['login'])) {
        header('location: login.php');
    }

    $user = $_SESSION['login']['user'];
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
        <title>Dashboard - SimPenBuk</title>
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
                        <?= $user['role']; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="shadow h-100 p-5 text-dark bg-light rounded-3" style="margin-top: 24px;">
                            <h2>Welcome to SimPenBuk</h2>
                            <p>Library Management System: Borrowing and Returning of Books.</p>
                        </div>
                        <div class="lead mt-4">
                            Borrowing Data <a href="borrowing.php" class="shadow-sm btn btn-primary btn-sm" style="float: right"><i class="fa fa-plus"></i> Create New</a>
                            <?php if ($user['role'] == 'Superadmin' || $user['role'] == 'Admin') : ?>
                                <a href="<?= $var->url;?>/json/exportdataborrowing.php" class="shadow-sm btn btn-success btn-sm" id="btnExport" style="float: right; margin-right: 10px"><i class="fa fa-file-excel"></i> Export</a>
                            <?php endif; ?>
                        </div>
                        <input type="text" class="form-control mt-4 shadow-sm" placeholder="Search" id="searchInput">
                        <table class="table table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Borrower</th>
                                  <th scope="col">Book</th>
                                  <th scope="col">Quantity</th>
                                  <th scope="col">Borrow Date</th>
                                  <th scope="col">Due Date</th>
                                  <th scope="col">Status</th>
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
        <div class="modal fade" id="modalConfirmReturn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Book Return Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formConfirmReturn">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="title_update" class="form-label">Book Title</label>
                                    <input id="title_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="name_update" class="form-label">Borrower Name</label>
                                    <input id="name_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="borrow_date_update" class="form-label">Borrow Date</label>
                                    <input id="borrow_date_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="due_date_update" class="form-label">Due Date</label>
                                    <input id="due_date_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="date_return_update" class="form-label">Date Return</label>
                                    <input id="date_return_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="penalties_update" class="form-label">Penalties</label>
                                    <input id="penalties_update" type="text" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="quantity_update" class="form-label">Quantity</label>
                                    <input id="quantity_update" rows="4" class="form-control shadow-sm" disabled>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="return_quantity_update" class="form-label">Return Quantity</label>
                                    <input id="return_quantity_update" rows="4" class="form-control shadow-sm">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="borrow_note_update" class="form-label">Borrow Note</label>
                                    <textarea id="borrow_note_update" rows="4" class="form-control shadow-sm" disabled></textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="return_note_update" class="form-label">Return Note</label>
                                    <textarea id="return_note_update" rows="4" class="form-control shadow-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Book Return Confirmation Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="title_detail" class="form-label">Book Title</label>
                                <input id="title_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="name_detail" class="form-label">Borrower Name</label>
                                <input id="name_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="borrow_date_detail" class="form-label">Borrow Date</label>
                                <input id="borrow_date_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="due_date_detail" class="form-label">Due Date</label>
                                <input id="due_date_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="date_return_detail" class="form-label">Date Return</label>
                                <input id="date_return_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="penalties_detail" class="form-label">Penalties</label>
                                <input id="penalties_detail" type="text" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="quantity_detail" class="form-label">Quantity</label>
                                <input id="quantity_detail" rows="4" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="return_quantity_detail" class="form-label">Return Quantity</label>
                                <input id="return_quantity_detail" rows="4" class="form-control shadow-sm" disabled>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="borrow_note_detail" class="form-label">Borrow Note</label>
                                <textarea id="borrow_note_detail" rows="4" class="form-control shadow-sm" disabled></textarea>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="return_note_detail" class="form-label">Return Note</label>
                                <textarea id="return_note_detail" rows="4" class="form-control shadow-sm" disabled></textarea>
                            </div>
                        </div>
                    </div>
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
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
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
                        url: '<?= $var->url; ?>/json/bookuser.php',
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
                                            ${item.name}
                                        </td>
                                        <td>${item.title}</td>
                                        <td>${item.quantity}</td>
                                        <td>${moment(item.borrow_date).format('DD MMMM YYYY')}</td>
                                        <td>${moment(item.due_date).format('DD MMMM YYYY')}</td>
                                        <td><span class="badge ${getBgBadge(item.status)} shadow-sm">${item.status}</span></td>
                                        <td>
                                            ${getButton(item)}
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

                $('#formConfirmReturn').on('submit', function(e) {
                    e.preventDefault()
                    Swal.fire({
                        title: 'Are you sure want to confirm?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Confirm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $var->url; ?>/json/confirmreturn.php?id='+currentConfirmId,
                                method: 'POST',
                                dataType: 'JSON',
                                data: {
                                    return_quantity: $('#return_quantity_update').val(),
                                    return_note: $('#return_note_update').val()
                                }, 
                                success: () => {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Data has been confirmed'
                                    })
                                    param.page = 1
                                    fetchData()
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Data failed to confirmed'
                                    })
                                    param.page = 1
                                    fetchData()
                                }
                            })
                        }
                    })
                })

                $(document).on('click', '.btnConfirmBorrow', function() {
                    let id = $(this).data('id')
                    Swal.fire({
                        title: 'Are you sure want to confirm?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Confirm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $var->url; ?>/json/confirmborrow.php',
                                method: 'GET',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                }, 
                                success: () => {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Data has been confirmed'
                                    })
                                    param.page = 1
                                    fetchData()
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Data failed to confirmed'
                                    })
                                    param.page = 1
                                    fetchData()
                                }
                            })
                        }
                    })
                })

                $(document).on('click', '.btnCancel', function() {
                    let id = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure want to cancel?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $var->url; ?>/json/cancelborrow.php',
                                method: 'GET',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                }, 
                                success: () => {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Data has been canceled'
                                    })
                                    param.page = 1
                                    fetchData()
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Data failed to canceled'
                                    })
                                    param.page = 1
                                    fetchData()
                                }
                            })
                        }
                    })
                })

                let currentConfirmId = ''
                $(document).on('click', '.btnConfirmReturn', function() {
                    let id = $(this).data('id')
                    currentConfirmId = id
                    $.ajax({
                        url: '<?= $var->url; ?>/json/showbookuser.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: (response) => {
                            $('#title_update').val(response.data.title)
                            $('#name_update').val(response.data.name)
                            $('#borrow_date_update').val(moment(response.data.borrow_date).format('DD MMMM YYYY'))
                            $('#due_date_update').val(moment(response.data.due_date).format('DD MMMM YYYY'))
                            $('#date_return_update').val(moment().format('DD MMMM YYYY'))
                            $('#quantity_update').val(response.data.quantity)
                            $('#return_quantity_update').val(response.data.quantity)
                            $('#borrow_note_update').val(response.data.borrow_note)

                            let penalties = 0
                            let diff = moment().diff(moment(response.data.due_date), 'days')

                            if (diff > 0) {
                                penalties = diff * 1000
                            }

                            $('#penalties_update').val(penalties)
                        }
                    })
                })

                $(document).on('click', '.btnDetail', function() {
                    let id = $(this).data('id')
                    $.ajax({
                        url: '<?= $var->url; ?>/json/showbookuser.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: (response) => {
                            $('#title_detail').val(response.data.title)
                            $('#name_detail').val(response.data.name)
                            $('#borrow_date_detail').val(moment(response.data.borrow_date).format('DD MMMM YYYY'))
                            $('#due_date_detail').val(moment(response.data.due_date).format('DD MMMM YYYY'))
                            $('#date_return_detail').val(moment(response.data.date_return).format('DD MMMM YYYY'))
                            $('#quantity_detail').val(response.data.quantity)
                            $('#return_quantity_detail').val(response.data.quantity)
                            $('#borrow_note_detail').val(response.data.borrow_note)
                            $('#return_note_detail').val(response.data.return_note)
                            $('#penalties_detail').val(response.data.penalties)
                        }
                    })
                })



                function getBgBadge(status) {
                    switch (status) {
                        case 'Booked':
                            return 'bg-info';

                        case 'Borrowed':
                            return 'bg-warning'

                        case 'Returned':
                            return 'bg-success'

                        case 'Canceled':
                            return 'bg-danger'

                        case 'Missing':
                            return 'bg-dark'
                    
                        default:
                            return 'bg-secondary'
                    }
                }

                function getButton(item) {
                    if (item.status == 'Booked') {
                        return `
                            <?php if ($user['role'] != 'Member'): ?>
                                <button class="btn btn-sm btn-primary shadow-sm btnConfirmBorrow" data-id="${item.id}">Confirm Borrow</button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-danger shadow-sm btnCancel" data-id="${item.id}">Cancel</button>
                        `
                    }

                    if (item.status == 'Borrowed') {
                        return `
                            <?php if ($user['role'] != 'Member'): ?>
                                <button class="btn btn-sm btn-success shadow-sm btnConfirmReturn" data-bs-toggle="modal" data-bs-target="#modalConfirmReturn" data-id="${item.id}">Confirm Return</button>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        `
                    }

                    if (item.status == 'Returned') {
                        return `
                            <button class="btn btn-sm btn-warning shadow-sm btnDetail" data-bs-toggle="modal" data-bs-target="#modalDetail" data-id="${item.id}">Detail</button>
                        `
                    }
                    return `-`;
                }

                function debounce(func, delay = 250) {
                    let timerId;
                    return (...args) => {
                        clearTimeout(timerId);
                        timerId = setTimeout(() => {
                            func.apply(this, args);
                        }, delay);
                    };
                }
            })
        </script>
    </body>
</html>
