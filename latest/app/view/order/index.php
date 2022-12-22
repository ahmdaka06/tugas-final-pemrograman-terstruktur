<?php
require_once 'layouts/primary.php';
require_once 'app/helper/order.helper.php';
$total_order = 0;
// session_destroy();
$carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>
<div class="row">

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
                                    <button class="btn btn-success btn-sm" onclick="addToCart(<?= $product['id'] ?>)">
                                        <i class="bi bi-cart"> </i>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2" x-data="getCart">
        <div class="card bg-light shadow">
            <div class="card-header bg-success">
                <h5 class="card-title  text-white"><i class="bi bi-cart"> </i>Keranjang</h5>
            </div>
            <div id="cart"></div>
        </div>
    </div>
</div>
<script>
    let cartLists = async() => {
        $.ajax({
            url: `${baseURL}api/cart`,
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                $('#cart').html(response.data);
            },
            error: function(response) {
                Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
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
    }
    let addToCart = (id) => {
        $.ajax({
            url: `${baseURL}api/cart`,
            type: 'POST',
            data: {
                action: 'addToCart',
                product: id,
                quantity: 1
            },
            dataType: 'JSON',
            success: function(response) {
                if (response.status == false) {
                    Swal.fire('Gagal!', response.msg, 'error');
                } else {
                    Toast.fire('Berhasil!', response.msg, 'success');
                    cartLists();
                }
            },
            error: function(data) {
                alert(data);
            },
        });
    }
    let checkoutCart = () => {
        Swal.fire({
            title: 'Apakah anda yakin akan melakukan pemesanan ini ?',
            showDenyButton: true,
            confirmButtonText: 'Ya',
            denyButtonText: `Tidak`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let data = new FormData();
                data.append('action', 'checkoutCart')
                $.ajax({
                    url: `${baseURL}api/cart`,
                    method: 'POST',
                    dataType: 'JSON',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            Toast.fire('Berhasil!', response.msg, 'success').then(() => {
                                window.location = '<?= url('order/invoice/') ?>' + response.orderID;
                            });
                            cartLists();
                        } else {
                            Swal.fire('Gagal!', response.msg, 'danger');
                            cartLists();
                        }
                    },
                    beforeSend: function() {
                        swal.fire({
                            title: 'Mohon tunggu...',
                            allowOutsideClick: false,
                                didOpen: function () {
                                    swal.showLoading()
                            }
                        });
                        $('#cart').html(`
                        <div class="text-center mt-2">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`);
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan pada sistem', 'danger');
                        cartLists();
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Berhasil Di Batalkan', '', 'success')
            }
        })
    }
    $(document).ready(function() {
        cartLists();
    });
</script>
<?php include 'layouts/footer.php'; ?>