<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Ahmad Andika X Dolanankode">
    <title>Final Project</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <script src="<?= url('assets/js/jquery.js') ?>"></script>
    <script src="<?= url('assets/js/jquery.bootstrap-touchspin.js') ?>"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>
    <script>
        var baseURL = '<?= url() ?>';
    </script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.1/axios.min.js"></script>
</head>

<body>

    <main>

        <div id="app">

            <div class="container py-4">
                <header class="pb-3 mb-4 border-bottom">
                    <a href="<?= url() ?>" class="text-dark text-decoration-none">
                        <span class="fs-4">BeliBro</span>
                    </a>
                    <a href="<?= url() ?>" class="float-end text-dark text-decoration-none">
                        <span class="fs-4 btn btn-success btn-sm position-relative">
                            <i class="bi bi-cart"> </i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">
                                {{ totalCarts }}
                            </span>
                        </span>
                    </a>
                </header>

                <div class="p-2 mb-4">
                    <div class="container-fluid py-1 pb-5">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="float-start">
                                    <?= get_day_indonesia(date('l')) ?>, <?= format_date(date('Y-m-d')) ?> {{ times }}
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= alert() ?>
                            </div>
                        </div>