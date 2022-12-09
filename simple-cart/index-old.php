<?php
require_once 'init.php';
require_once 'layouts/primary.php';

$total_order = 0;
$carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];


if (!empty($carts)) {
    foreach ($carts as $cart) {
        $total_order += $cart['price'] * $cart['quantity'];
    }
}
require_once 'app/process/order.php';
?>
<div class="row" x-data="alpineJS">

    <div class="col-md-6 mb-5">
        <div class="card bg-light shadow">
            <div class="card-header bg-success">
                <h5 class="card-title text-white"><i class="bi bi-tags"> </i>Belanja</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered nowrap">
                        <thead class="bg-success text-white">
                            <tr>
                                <td>Produk</td>
                                <td>Harga</td>
                                <td class="text-center">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($config['products'])) { ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    Keranjang masih kosong seperti hati ku...
                                </td>
                            </tr>
                            <?php } else { ?>
                                <?php foreach ($config['products'] as $product){ ?>
                                <tr>
                                    <td><i class="bi bi-tag"> </i> <?= $product['name'] ?> </td>
                                    <td>Rp <?= currency($product['price']) ?></td>
                                    <td class="text-center">
                                        <button 
                                            class="btn btn-success btn-sm"
                                            @click="addToCart(<?= $product['id'] ?>)"
                                        >
                                            <i class="bi bi-cart"> </i> 
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="form-group mt-2">
                        <label for="">Produk</label>
                        <select name="product" class="form-control" required>
                            <option value=""> - Pilih Salah Satu - </option>
                            <?php foreach ($config['products'] as $key => $value) { ?>
                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?> | Rp
                                <?= currency($value['price']) ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                            Harap memilih salah satu produk.
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" placeholder="ex: 1" required>
                        <div class="invalid-feedback">
                            Harap mengisi jumlah .
                        </div>
                    </div>
                    <div class="form-group mt-2 text-center">
                        <button class="btn btn-success fw-bold" name="order"><i class="bi bi-cart-plus"> </i>
                            Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="card bg-light shadow">
            <div class="card-header bg-success">
                <h5 class="card-title  text-white"><i class="bi bi-cart"> </i>Keranjang</h5>
            </div>
            <div class="card-body">
                <?php
                if (!empty($carts)) { ?>
                <a href="<?= url('index.php?action=empty_cart') ?>"
                    onclick="return confirm(`Apakah anda ingin menghapus mengkosongkan keranjang ini ?`)"
                    class="btn btn-danger mb-2">
                    <i class="bi bi-trash3"> </i>
                    Kosongkan Keranjang
                </a>
                <?php } ?>
                <form action="" method="POST">
                    <div class="table-responsive">
                        <table class="table table-bordered nowrap">
                            <thead class="bg-success text-white">
                                <tr>
                                    <td>Produk</td>
                                    <td>Harga</td>
                                    <td>Jumlah</td>
                                    <td>Total</td>
                                    <td class="text-center">Aksi</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($carts)) { ?>
                                <tr>
                                    <td colspan="5" style="text-align:center;">
                                        Keranjang masih kosong seperti hati ku...
                                    </td>
                                </tr>
                                <?php } else { ?>
                                <?php foreach ($carts as $cart){ ?>
                                <tr>
                                    <td><i class="bi bi-tag"> </i> <?= $cart['name'] ?> </td>
                                    <td>Rp <?= currency($cart['price']) ?></td>
                                    <td>
                                        <input type="hidden" name="cart_product_id[<?= $cart['id'] ?>]"
                                            value="<?= $cart['id'] ?>">
                                        <input type="number" name="cart_quantity[<?= $cart['id'] ?>]"
                                            class="form-control form-control-sm touchspin"
                                            value="<?= $cart['quantity'] ?>">
                                    </td>
                                    <td>Rp <?= currency($cart['price'] * $cart['quantity']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= url('index.php?action=delete_cart&product_id=' . $cart['id']) ?>"
                                            onclick="return confirm(`Apakah anda ingin menghapus produk ini ?`)"
                                            class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash3"> </i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($carts)) { ?>
                    <button class="btn btn-warning float-end text-white fw-bold" type="submit" name="update"><i
                            class="bi bi-pencil"> </i>Update Keranjang</button>
                    <?php } ?>
                </form>
            </div>
            <?php if (!empty($cart)) { ?>
            <div class="card-footer">
                <div class="col-md-12">
                    <div class="table-responsive ">
                        <table class="table table-borderless text-end">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Sub Total</strong>
                                    </td>
                                    <td>
                                        Rp <?= currency($total_order) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Total Potongan</strong>
                                    </td>
                                    <td>
                                        Rp <?= currency(get_amount_discount($total_order)) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Total Pesanan</strong>
                                    </td>
                                    <td>
                                        Rp <?= currency($total_order - get_amount_discount($total_order)) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="pb-2 mt-b">
                    <div class="float-end">
                        <?php if (get_discount($total_order) > 0) { ?>
                        <p class="text-danger fw-bold">
                            <em>Anda mendapatkan diskon sebesar <?= get_discount($total_order) ?>%</em>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $(".touchspin").TouchSpin({
        step: 1,
        min: 1,
        max: 10,
        buttondown_class: 'btn btn-success btn-sm',
        buttonup_class: 'btn btn-success btn-sm',
    });
</script>
<script src="main.js"></script>
<?php include 'layouts/footer.php'; ?>