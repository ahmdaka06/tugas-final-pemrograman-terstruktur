<?php
require_once '../../../init.php';

require_once '../../../layouts/primary.php';

$id = (isset($_GET['id'])) ? $_GET['id'] : false;
if ($id == false) {
    flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanan tidak ditemukan!.']);
    exit(redirect(url()));
}
$orderHistory = isset($_SESSION['orderHistory'][$id]) ? $_SESSION['orderHistory'][$id] : false;
if ($orderHistory == false) {
    flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanan tidak ditemukan!.']);
    exit(redirect(url()));
}
?>
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-warning float-start mb-3" href="<?= url() ?>">Kembali</a>
    </div>
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-success  py-3">
                <h5 style="text-white">Invoice >> <strong>ID: #<?= $id ?></strong></h5>
            </div>
            <div class="card-body">
                <div class="container mb-5 mt-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8">
                                <ul class="list-unstyled">
                                    <li class="text-muted">Kepada: <span class="text-center fw-bold">Customer</span></li>
                                    <li class="text-muted">Bareng Is The Best</li>
                                    <li class="text-muted">Jombang, Indonesia</li>
                                    <li class="text-muted"><i class="fas fa-phone"></i> 123-456-789</li>
                                </ul>
                            </div>
                            <div class="col-xl-4">
                                <p class="fw-bold">Invoice</p>
                                <ul class="list-unstyled">
                                    <li class="text-muted"><i class="fas fa-circle"></i> 
                                        <span class="fw-bold">ID:   </span>#<?= $id ?>
                                    </li>
                                    <li class="text-muted"><i class="fas fa-circle"></i> 
                                        <span class="fw-bold">Tanggal Transaksi:</span> <?= format_date($orderHistory['created_at']) ?>
                                    </li>
                                    <li class="text-muted"><i class="fas fa-circle"></i> 
                                        <span class="me-1 fw-bold">Status:</span>
                                        <span class="badge bg-success text-black fw-bold"> Sukses</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Produk</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Harga Produk</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $i = 1;
                                            foreach ($orderHistory['items'] as $key => $value) { 
                                        ?> 
                                        <tr>
                                            <th scope="row"><?= $i++ ?></th>
                                            <td><?= $value['name'] ?></td>
                                            <td><?= currency($value['quantity']) ?></td>
                                            <td>Rp <?= currency($value['price']) ?></td>
                                            <td>Rp <?= currency($value['quantity'] * $value['price']) ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-8">
                                
                            </div>
                            <div class="col-xl-3">
                                <ul class="list-unstyled">
                                    <li class="text-muted"><span class="text-black me-4">SubTotal</span>Rp <?= currency($orderHistory['totalOrderAmount']) ?></li>
                                    <li class="text-muted mt-2"><span class="text-black me-4">Diskon(<?= $orderHistory['discountTotal'] ?>)</span>Rp <?= currency($orderHistory['discountAmount']) ?></li>
                                    <li>
                                    <p class="text-black float-start">
                                        <span class="text-black me-3"> Total Amount</span>
                                        <span> <strong>Rp <?= currency($orderHistory['totalOrderAmount'] - $orderHistory['discountAmount']) ?></strong></span>
                                    </p>
                                    </li>
                                </ul>
                                
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-10">
                                <p>Terima kasih telah berbelanja di <strong>BeliBro</strong></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../../../resources/main.js.php' ?>
<?php require_once '../../../layouts/footer.php'; ?>