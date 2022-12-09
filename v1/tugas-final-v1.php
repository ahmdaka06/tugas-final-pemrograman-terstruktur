<html>
<?php 
date_default_timezone_set('Asia/Jakarta'); // set default timezone to asia/jakarta alias wib
?>
<head>
    <meta name="color-scheme" content="light dark">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (as per normal) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Hello, world!</title>
    <!-- Optional Meta Theme Color is also supported on Safari and Chrome -->
    <meta name="theme-color" content="#111111" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#eeeeee" media="(prefers-color-scheme: dark)">
    <style>
        label {
            margin-bottom: .5rem;
        }
    </style>
</head>
<?php
function currency(float|int $value = 0){
    $value = ceil($value);
    return number_format($value, 0, ".", ".");
}
function get_day_indonesia(string $day){
    $day = strtolower($day);
    $today_arr = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jum\'at',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu'
    ];
    return isset($today_arr[$day]) ? $today_arr[$day] : $day;
}
function get_discount(int $amount)
{
    $get_day_indonesia = strtolower(get_day_indonesia(date('l')));
    if ($amount >= 300000 && $amount <= 500000) return ($get_day_indonesia == 'minggu') ? (10 + 5) : 10;
    elseif ($amount > 500000 && $amount <= 700000) return ($get_day_indonesia == 'minggu') ? (12 + 5) : 12;
    elseif ($amount > 700000 && $amount <= 900000) return ($get_day_indonesia == 'minggu') ? (14 + 5) : 14;
    else return ($get_day_indonesia == 'minggu') ? (0 + 5) : 0;
}

function get_amount_discount(int $amount)
{
    return ceil($amount * (get_discount($amount) / 100));
}
function get_amount_after_discount(int $amount, int $amount_discount)
{
    return ceil($amount - $amount_discount);
}
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 text-center">
                <h1>Simple V1</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">
                            Belanja
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group mt-2">
                                <label for="">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" value="<?= $_POST['nama_barang'] ?? null ?>">
                            </div>
                            <div class="form-group mt-2">
                                <label for="">Harga Barang</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control" name="harga_barang" value="<?= $_POST['harga_barang'] ?? null ?>" placeholder="10000">
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" name="submit" class="btn btn-primary text-white">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3 mt-sm-0">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">
                            Result
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        if (isset($_POST['submit'])) {
                            $nama_barang = $_POST['nama_barang'] ?? null;
                            $harga_barang = $_POST['harga_barang'] ?? null;

                            if (!$nama_barang OR !$harga_barang) {
                                exit('<p class="text-center text-danger fw-bold">Permintaan tidak sesuai</p>');
                            } else if (is_numeric($_POST['harga_barang']) == false) {
                                exit('<p class="text-center text-danger fw-bold">Harga barang tidak sesuai</p>');
                            } else {
                        ?> 
                        <div class="row">
                            <div class="form-group mt-2 col-12">
                                <label for="">Nama Barang</label>
                                <input type="text" class="form-control" value="<?= $_POST['nama_barang'] ?? null ?>" readonly>
                            </div>
                            <div class="form-group mt-2 col-12">
                                <label for="">Harga Barang</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control" value="<?= currency($_POST['harga_barang'] ?? 0) ?>" readonly>
                                </div>
                            </div>
                            <label for="">Diskon</label>
                            <div class="form-group mt-2 col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">%</span>
                                    <input type="text" class="form-control" value="<?= get_discount($harga_barang) ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-2 col-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control" value="<?= currency(get_amount_discount($harga_barang)) ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-2 col-12">
                                <label for="">Harga Akhir</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp </span>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        value="<?= currency(get_amount_after_discount($harga_barang, get_amount_discount($harga_barang))) ?>" 
                                        readonly
                                        >
                                </div>
                            </div>
                        </div>
                        <?php 
                            } 
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>