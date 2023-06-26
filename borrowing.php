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
        <title>Borrowing - SimPenBuk</title>
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
                        <div class="lead mt-4">
                            Create Book Borrowing Data <a href="index.php" class="shadow-sm btn btn-primary btn-sm" style="float: right"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="row mt-5" style="max-width: 800px; margin: auto">
                            <div class="shadow p-4 mb-5" style="border-radius: 10px;">
                                <div class="lead" style="font-size: 25px; font-weight: 500">Book Information</div>
                                <div class="row mt-3">
                                    <div class="col-md-12 mb-4">
                                        <input type="text" class="form-control shadow-sm" id="searchInputBook" placeholder="Search">
                                        <div style="position: relative">
                                            <div class="bg-light shadow tbody" id="autocomplete-item" style="position: absolute;width: 100%; border-radius: 10px; max-height: 200px; overflow-y: auto; overflow-x: hidden; display:none">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <img src="./uploads/default_placeholder.png" class="img-responsive shadow-sm bookImage" style="width: 100%; cursor: pointer;" alt="">
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
                                    </div>
                                </div>
                            </div>

                            <div class="shadow p-4 mb-5" style="border-radius: 10px;">
                                <div class="lead" style="font-size: 25px; font-weight: 500">Book Borrowing Information</div>
                                <div class="row mt-3">
                                    <div class="mt-3 col-md-6">
                                        <label for="borrow_date_new" class="form-label">Borrow Date</label>
                                        <input type="date" class="shadow-sm form-control" id="borrow_date_new" value="<?= Carbon::now()->format('Y-m-d');?>">
                                    </div>
                                    <div class="mt-3 col-md-6">
                                        <label for="due_date_new" class="form-label">Due Date <span class="small">(Borrow Date + 7 Days)</span></label>
                                        <input type="date" class="shadow-sm form-control" id="due_date_new" value="<?= Carbon::now()->addDays(7)->format('Y-m-d');?>" disabled>
                                    </div>
                                    <div class="mt-3 col-md-6">
                                        <label for="quantity_new" class="form-label">Quantity</label>
                                        <input type="number" class="shadow-sm form-control" id="quantity_new" value="1">
                                    </div>
                                    <div class="mt-3 col-md-6">
                                        <label for="notes_new" class="form-label">Notes</label>
                                        <textarea rows="4" class="form-control shadow-sm" id="notes_new"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="shadow p-4 mb-5" style="border-radius: 10px;">
                                <div class="lead mb-3" style="font-size: 25px; font-weight: 500">Borrower Information</div>                            
                                <div class="row">
                                    <?php if ($user['role'] == 'Admin' || $user['role'] == 'Superadmin'): ?>
                                        <div class="col-md-12 mb-4">
                                            <input type="text" class="form-control shadow-sm" id="searchInputUser" placeholder="Search">
                                            <div style="position: relative">
                                                <div class="bg-light shadow tbody-user" id="autocomplete-item-user" style="position: absolute;width: 100%; border-radius: 10px; max-height: 200px; overflow-y: auto; overflow-x: hidden; display:none">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name_new" class="form-label">Name</label>
                                            <input type="text" class="shadow-sm form-control" id="name_new" <?= $user['role'] == 'Member' ? 'value="'.$user['name'].'"' : ''?> disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email_new" class="form-label">Email</label>
                                            <input type="text" class="shadow-sm form-control" id="email_new"  <?= $user['role'] == 'Member' ? 'value="'.$user['email'].'"' : ''?> disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone_new" class="form-label">Phone</label>
                                            <input type="text" class="shadow-sm form-control" id="phone_new"  <?= $user['role'] == 'Member' ? 'value="'.$user['phone'].'"' : ''?> disabled>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="gender_new" class="form-label">Gender</label>
                                            <input type="text" class="shadow-sm form-control" id="gender_new"  <?= $user['role'] == 'Member' ? 'value="'.($user['gender'] == 0 ? 'Male': 'Female').'"' : ''?> disabled>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="address_new" class="form-label">Address</label>
                                            <textarea rows="4" class="form-control shadow-sm" id="address_new" disabled><?= $user['role'] == 'Member' ? $user['address'] : ''?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mb-5 btnSaveBorrow" style="width: 100px"><i class="fa fa-save"></i> Save</button>
                        </div>
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
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script>
            $(document).ready(async function() {
                let currentBook = {}
                let currentUser = {
                    id: '<?= $user['id']?>'
                }
                let currentBooks = []
                let currentUsers = []

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
                            page: 1,
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
                                        <td>${item.borrow_date}</td>
                                        <td>${item.due_date}</td>
                                        <td><span class="badge ${getBgBadge(item.status)} shadow-sm">${item.status}</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-success shadow-sm btnUpdate" data-id="${item.id}" data-bs-toggle="modal" data-bs-target="#updateMember"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-sm btn-danger shadow-sm btnDelete" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                `)
                            })
                            
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    }); 
                }

                $('#searchInputBook').on('input', debounce(function() {
                    if ($('#searchInputBook').val() == '') {
                        $('#autocomplete-item').css('display', 'none')
                        return
                    }
                    $('#autocomplete-item').css('display', 'inline-block')
                    fetchBook()
                }))

                $('#searchInputUser').on('input', debounce(function() {
                    console.log("TEST")
                    if ($('#searchInputUser').val() == '') {
                        $('#autocomplete-item-user').css('display', 'none')
                        return
                    }
                    $('#autocomplete-item-user').css('display', 'inline-block')
                    fetchMember()
                }))

                $('#borrow_date_new').on('change', function() {
                    $('#due_date_new').val(moment($(this).val()).add(7, 'days').format('YYYY-MM-DD'))
                })

                $(document).on('click', '.itemauto', function() {
                    $('#autocomplete-item').css('display', 'none')
                    $('#searchInputBook').val('')
                    let id = $(this).data('id')
                    currentBook = currentBooks.filter((item) => {
                        return item.id == id
                    })[0]

                    $('#title_update').val(currentBook.title)
                    $('#writer_update').val(currentBook.writer)
                    $('#publisher_update').val(currentBook.publisher)
                    $('#stock_update').val(currentBook.stock)
                    $('#note_update').val(currentBook.note)
                    $('.bookImage').attr('src', `./uploads/${currentBook.thumbnail}`)
                })

                $(document).on('click', '.itemautouser', function() {
                    $('#autocomplete-item-user').css('display', 'none')
                    $('#searchInputUser').val('')
                    let id = $(this).data('id')
                    currentUser = currentUsers.filter((item) => {
                        return item.id == id
                    })[0]

                    $('#name_new').val(currentUser.name)
                    $('#email_new').val(currentUser.email)
                    $('#phone_new').val(currentUser.phone)
                    $('#gender_new').val(currentUser.gender)
                    $('#address_new').val(currentUser.address)
                })

                $('.btnSaveBorrow').on('click', function() {
                    Swal.fire({
                        title: 'Please Double Check Your Input',
                        text: "After save this data, you cannot change again",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= $var->url; ?>/json/createbookuser.php',
                                method: 'POST',
                                dataType: 'JSON',
                                data: {
                                    user_id: currentUser.id,
                                    book_id: currentBook.id,
                                    quantity: $('#quantity_new').val(),
                                    borrow_date: $('#borrow_date_new').val(),
                                    due_date: $('#due_date_new').val(),
                                    borrow_note: $('#notes_new').val(),
                                }, 
                                success: async () => {
                                    await Toast.fire({
                                        icon: 'success',
                                        title: 'Data has been created'
                                    })
                                    let path = window.location.pathname.split('/')
                                    delete path[path.length-1]

                                    window.location = path.join('/')+'index.php'
                                },
                                error: () => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Data failed to deleted'
                                    })
                                }
                            })
                        }
                    })
                })

                function fetchBook() {
                    let tbody = $('.tbody')
                    tbody.empty()
                    tbody.append(`
                        <div class="row p-3" style="cursor: pointer;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    `)
                    
                    return $.ajax({
                        url: '<?= $var->url; ?>/json/book.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            page: 1,
                            search: $('#searchInputBook').val()
                        }, 
                        success: (response) => {
                            tbody.empty()
                            if (!response.data.length) {
                                tbody.append(`
                                    <div class="row p-3" style="cursor: pointer;">
                                        <div class="col-md-12 p-3 d-flex align-items-center">
                                            <div>
                                                No Data
                                            </div>
                                        </div>
                                    </div>
                                `)
                            }
                            currentBooks = response.data
                            response.data.forEach((item, index) => {
                                tbody.append(`
                                    <div class="row p-3 itemauto" data-id="${item.id}" style="cursor: pointer;">
                                        <div class="col-md-2">
                                            <img src="./uploads/${item.thumbnail}" class="img-responsive shadow-sm" style="width: 100%; border-radius: 10px" alt="">
                                        </div>
                                        <div class="col-md-10 p-3 d-flex align-items-center">
                                            <div>
                                                <div class="lead">${item.title}</div>
                                                <div class="small" style="font-style: italic">by ${item.writer}</div>
                                            </div>
                                        </div>
                                    </div>
                                `)
                            })
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                }

                function fetchMember() {
                    let tbody = $('.tbody-user')
                    tbody.empty()
                    tbody.append(`
                        <div class="row p-3" style="cursor: pointer;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    `)
                    
                    return $.ajax({
                        url: '<?= $var->url; ?>/json/member.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            page: 1,
                            search: $('#searchInputUser').val()
                        }, 
                        success: (response) => {
                            tbody.empty()
                            if (!response.data.length) {
                                tbody.append(`
                                    <div class="row p-3" style="cursor: pointer;">
                                        <div class="col-md-12 p-3 d-flex align-items-center">
                                            <div>
                                                No Data
                                            </div>
                                        </div>
                                    </div>
                                `)
                            }
                            currentUsers = response.data
                            response.data.forEach((item, index) => {
                                tbody.append(`
                                    <div class="row p-3 itemautouser" data-id="${item.id}" style="cursor: pointer;">
                                        <div class="col-md-2">
                                            <img src="./uploads/${item.picture}" class="img-responsive shadow-sm" style="width: 100%; border-radius: 10px" alt="">
                                        </div>
                                        <div class="col-md-10 p-3 d-flex align-items-center">
                                            <div>
                                                <div class="lead">${item.name}</div>
                                                <div class="small" style="font-style: italic">${item.email}</div>
                                            </div>
                                        </div>
                                    </div>
                                `)
                            })
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                }

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
