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
            <tr data-id="<?= $cart['id'] ?>">
                <td><i class="bi bi-tag"> </i> <?= $cart['name'] ?> </td>
                <td>Rp <?= currency($cart['price']) ?></td>
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control form-control-sm touchspin quantity" value="<?= $cart['quantity'] ?>" style="width: 100%; !important" />
                    </div>
                </td>
                <td>Rp <?= currency($cart['price'] * $cart['quantity']) ?></td>
                <td class="text-center">
                    <button
                        class="btn btn-warning btn-sm update-cart m-2">
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
</div>
<?php if (!empty($carts)) { ?>
<button class="btn btn-warning float-end text-white fw-bold" type="submit" name="update"><i class="bi bi-pencil">
    </i>Update Keranjang</button>
<?php } ?>
<script>
    $(document).ready(function() {
        $('.update-cart').on('click', function (e) {
            e.preventDefault();
            var el = $(this);
            $.ajax({
                url: `${baseURL}api/cart`,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'updateCart',
                    product: el.parents('tr').attr('data-id'),
                    quantity: el.parents('tr').find('.quantity').val()
                },
                success: function (response) {
                    if (response.status == true) {
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