<?php
require_once '../../init.php';
$total_order = 0;
$carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];


if (!empty($carts)) {
    foreach ($carts as $cart) {
        $total_order += $cart['price'] * $cart['quantity'];
    }
}
?>
<div class="card-body">
    <form action="#" class="cart-table-form" method="POST">
        <div class="table-responsive">
            <table class="table table-bordered nowrap">
                <thead class="bg-success text-white">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th class="text-center">Aksi</th>
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
                        <tr data-id="<?= $cart['id'] ?>">
                            <td><i class="bi bi-tag"> </i> <?= $cart['name'] ?> </td>
                            <td>Rp <?= currency($cart['price']) ?></td>
                            <td>
                                <div class="input-group">
                                    <input type="hidden" name="product[<?= $cart['id'] ?>]" value="<?= $cart['id'] ?>">
                                    <input type="number" class="form-control form-control-sm touchspin quantity"
                                        name="quantity[<?= $cart['id'] ?>]" value="<?= $cart['quantity'] ?>" style="width: 100%; !important" />
                                </div>
                            </td>
                            <td>Rp <?= currency($cart['price'] * $cart['quantity']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm update-cart m-2">
                                    <i class="bi bi-pencil"> </i>
                                </button>
                                <button class="btn btn-danger btn-sm remove-from-cart m-2">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        <?php if (!empty($carts)) { ?>
        <button class="btn btn-warning float-end text-white fw-bold" type="submit" name="update"><i
                class="bi bi-pencil">
            </i>Update Keranjang
        </button>
        <?php } ?>
        </div>
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
    <div class="pb-2 my-2">
        <div class="float-end">
            <?php if (get_discount($total_order) > 0) { ?>
            <p class="text-danger fw-bold">
                <em>Anda mendapatkan diskon sebesar <?= get_discount($total_order) ?>%</em>
            </p>
            <?php } ?>
        </div>
    </div>
    
    <div class="pb-2 my-5">
        <div class="d-flex justify-content-center">
            <?php if (!empty($cart)) { ?>
                <button class="btn btn-success" onclick="checkoutCart()"><i class="bi bi-cart-plus"></i> Checkout</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
<script>
    
    $(document).ready(function() {
        $('.cart-table-form').on('submit', function (e) {
            e.preventDefault();
            let data = new FormData(this);
            data.append('action', 'updateCartAll')
            console.log(data);
            $.ajax({
                url: `${baseURL}api/cart`,
                method: 'POST',
                dataType: 'JSON',
                data: data,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status == true) {
                        Toast.fire('Berhasil!', response.msg, 'success');
                        cartLists();
                    } else {
                        Swal.fire('Gagal!', response.msg, 'danger');
                        cartLists();
                    }
                },
                beforeSend: function() {
                    $('#cart').html(`
                    <div class="text-center mt-2">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`);
                }
            });
        });
        $('.update-cart').on('click', function (e) {
            e.preventDefault();
            var el = $(this);
            $.ajax({
                url: `${baseURL}api/cart`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'updateCartOne',
                    product: el.parents('tr').attr('data-id'),
                    quantity: el.parents('tr').find('.quantity').val()
                },
                success: function (response) {
                    if (response.status == true) {
                        Toast.fire('Berhasil!', response.msg, 'success');
                        cartLists();
                    } else {
                        Swal.fire('Gagal!', response.msg, 'danger');
                        cartLists();
                    }
                },
                beforeSend: function() {
                    $('#cart').html(`
                    <div class="text-center mt-2">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`);
                }
            });
        });

        $('.remove-from-cart').click(function (e) {
            e.preventDefault();
            var el = $(this);
            $.ajax({
                url: `${baseURL}api/cart`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'removeCart',
                    product: el.parents('tr').attr('data-id'),
                },
                success: function (response) {
                    if (response.status == true) {
                        Toast.fire('Berhasil!', response.msg, 'success');
                        cartLists();
                    } else {
                        Swal.fire('Gagal!', response.msg, 'danger');
                        cartLists();
                    }
                },
                beforeSend: function() {
                    $('#cart').html(`
                        <div class="text-center mt-2">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`);                
                }
            });
        });
    });
</script>