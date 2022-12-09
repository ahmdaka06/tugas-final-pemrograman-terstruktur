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
        var baseURL = '<?= url() ?>';
    </script>
    
</head>

<body>

    <main>
        <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">
                <a href="<?= url() ?>" class="d-flex align-items-center text-dark text-decoration-none">
                    <span class="fs-4">Tugas</span>
                </a>
            </header>

            <div class="p-2 mb-4">
                <div class="container-fluid py-1 pb-5">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6 class="float-start">
                                <?= $get_day_indonesia ?>, <?= format_date(date('Y-m-d')) ?>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= alert() ?>
                        </div>
                    </div>



