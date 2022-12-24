<script> 
function loading() {
    swal.fire({
        title: 'Mohon tunggu...',
        allowOutsideClick: false,
            didOpen: function () {
                swal.showLoading()
        }
    });
}
const app = {
    data() {
        return {
            message: 'Hello',
            products: [],
            carts: [],
            orderHistories: {
                total: 0,
                data: []
            },
            totalCarts: 0,
            priceSubTotal: 0,
            discountPrice: 0,
            lastPrice: 0,
            discount: 0,
            times: `<?= date('H:i:s') ?>`
        }
    },
    async mounted() {
        this.fetchProducts();
        this.fetchCarts();
        this.fetchOrderHistories();
        setInterval(async () => this.setTime(), 1000);
    },
    methods: {
        async fetchProducts(){
            return axios.get(`<?= url('api/products') ?>`)
                .then((result) => {
                    this.products = result.data.data;
                }).catch((err) => {
                    
                });
        },
        async fetchCarts(){
            return axios.get(`<?= url('api/cart') ?>`)
                .then((result) => {
                    let response = result.data;
                    this.carts = response.data.items;
                    this.totalCarts = response.data.totalItems ?? 0;
                    this.priceSubTotal = response.data.priceSubTotal;
                    this.discountPrice = response.data.discountPrice;
                    this.lastPrice = response.data.lastPrice;
                    this.discount = response.data.discount;
                }).catch((err) => {
                    
                });
        },
        async fetchOrderHistories(){
            return axios.get(`<?= url('api/order?__m=history') ?>`)
                .then((result) => {
                    let response = result.data;
                    this.orderHistories = response;
                }).catch((err) => {
                    
                });
        },
        addToCart(id) {
            loading();
            return axios.post(`<?= url('api/cart') ?>`, {
                action: 'addToCart',
                product: id,
                quantity: 1
            }).then((response) => {
                if (response.data.status == false) {
                    Swal.fire('Gagal!', response.data.msg, 'error');
                } else {
                    this.fetchCarts();
                    Toast.fire('Berhasil!', response.data.msg, 'success');
                }
            }).catch((err) => {

            }); 
        },
        updateCart(id, type) {
            return axios.post(`<?= url('api/cart') ?>`, {
                action: 'updateCart',
                product: id,
                type: type
            }).then((response) => {
                if (response.data.status == false) {
                    Swal.fire('Gagal!', response.data.msg, 'error');
                } else {
                    this.fetchCarts();
                }
            }).catch((err) => {

            }); 
        },
        removeCart(id, type) {
            return axios.post(`<?= url('api/cart') ?>`, {
                action: 'removeCart',
                product: id,
            }).then((response) => {
                if (response.data.status == false) {
                    Swal.fire('Gagal!', response.data.msg, 'error');
                } else {
                    this.fetchCarts();
                }
            }).catch((err) => {

            }); 
        },
        checkoutCart() {
            Swal.fire({
                title: 'Apakah anda yakin akan melakukan pemesanan ini ?',
                showDenyButton: true,
                confirmButtonText: 'Ya',
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    loading();
                    return axios.post(`<?= url('api/cart') ?>`, {
                        action: 'checkoutCart',
                    }).then((response) => {
                        if (response.data.status == false) {
                            Swal.fire('Gagal!', response.data.msg, 'error');
                        } else {
                            this.fetchCarts();
                            Swal.fire('Berhasil!', response.data.msg, 'success').then(() => {
                                window.location = '<?= url('order/invoice/') ?>' + response.data.orderID;
                            });
                        }
                    }).catch((err) => {

                    }); 
                } else if (result.isDenied) {
                    Swal.fire('Berhasil Di Batalkan', '', 'success')
                }
            })
        },
        sumSubTotal(items) {
            return items.reduce((total, item) => {
                console.log('oi');
                return total + (item.price * item.quantity)
            }, 0)
        },
        currencyIDR(amount){
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
                }).format(amount).replace(',00', '');
        },
        setTime() {
            const date = new Date();
            let hours = date.getHours();
            let minutes = date.getMinutes();
            let seconds = date.getSeconds();
            hours = hours <= 9 ? `${hours}`.padStart(2, 0) : hours;
            minutes = minutes <= 9 ? `${minutes}`.padStart(2, 0) : minutes;
            seconds = seconds <= 9 ? `${seconds}`.padStart(2, 0) : seconds;
            this.times = `${hours}:${minutes}:${seconds}`
        }
    }
}
Vue.createApp(app).mount('#app');
</script>