<?php
require_once 'init.php';
require_once 'layouts/primary.php';
require_once 'app/helper/order.helper.php';
$total_order = 0;
// session_destroy();
$carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>

<div class="row">
    <template v-for="product in products" :key="product.id">
        <div class="col-md-3 my-5">
            <div class="card shadow">
                <img v-bind:src="product.img" class="card-img-top" v:bind:alt="product.name" style="height: 250px; width: 100%">
                <div class="card-body">
                    <h5 class="card-title">{{ product.name }}</h5>
                    <p class="card-text">{{ currencyIDR(product.price) }}</p>
                    <div class="text-center">
                        <button v-on:click="addToCart(product.id)" class="btn btn-success btn-sm"><i class="bi bi-cart"> </i></button>
                    </div>
                    
                </div>
            </div>
        </div>
    </template>
    <div class="col-md-12 my-3">
        <div class="card bg-light shadow">
            <div class="card-header bg-success">
                <h5 class="card-title  text-white"><i class="bi bi-cart"> </i>Keranjang</h5>
            </div>
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
                                <template v-if="totalCarts == 0">
                                    <tr>
                                        <td colspan="5" style="text-align:center;">
                                            Keranjang masih kosong seperti hati ku...
                                        </td>
                                    </tr>
                                </template>
                                <template v-else v-for="cart in carts" :key="cart.id">
                                    <tr v:bind:data-id="cart.id">
                                        <td><i class="bi bi-tag"> </i> {{ cart.name }} </td>
                                        <td>{{ currencyIDR(cart.price) }}</td>
                                        <td>
                                            {{ cart.quantity }}
                                        </td>
                                        <td>{{ currencyIDR(cart.price * cart.quantity) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm m-2" v-on:click="updateCart(cart.id, 'increment')" type="button">
                                                <i class="bi bi-plus-square"> </i>
                                            </button>
                                            <button class="btn btn-primary btn-sm m-2" v-on:click="updateCart(cart.id, 'decrement')" type="button" :disabled="cart.quantity == 1">
                                                <i class="bi bi-dash-square"> </i>
                                            </button>
                                            <button class="btn btn-danger btn-sm m-2" v-on:click="removeCart(cart.id)" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <template v-if="totalCarts > 0">
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
                                            {{ currencyIDR(priceSubTotal) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Total Potongan</strong>
                                        </td>
                                        <td>
                                            {{ currencyIDR(discountPrice) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Total Pesanan</strong>
                                        </td>
                                        <td>
                                            {{ currencyIDR(lastPrice) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="float-end">
                                    <p class="text-danger fw-bold" v-if="discount > 0">
                                        <em>Anda mendapatkan diskon sebesar {{ discount }}%</em>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success" v-on:click="checkoutCart()"><i class="bi bi-cart-plus"></i> Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<div class="row my-3">
    <div class="col-md-12">
       <div class="card shadow">
        <div class="card-header bg-success">
                <h5 class="card-title text-white"><i class="bi bi-cart4"> </i>Riwayat Belanja</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered nowrap">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>#</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Nomor Faktur</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="orderHistories.total == 0">
                                <tr>
                                    <td colspan="5" style="text-align:center;">
                                        Keranjang masih kosong seperti hati ku...
                                    </td>
                                </tr>
                            </template>
                            <template v-else v-for="order in orderHistories.data" :key="order.uid">
                                <tr>
                                    <td>{{ order.uid }}</td>
                                    <td>{{ order.created_at }}</td>
                                    <td>
                                        <a :href="'<?= url('order/invoice/') ?>' +  order.uid" class="btn btn-success">#{{ order.uid }}</a>
                                    </td>
                                    <td>{{ currencyIDR(order.totalOrderAmount - order.discountAmount) }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
       </div>                         
    </div>                    
</div>
<?php include 'resources/main.js.php' ?>


<?php include 'layouts/footer.php'; ?>