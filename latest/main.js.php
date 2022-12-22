<script>
function alpineJS() {
    
    return {
        cart: [],
        products: [],
        history: [],
        // async init() {
        //     const carts = await axios(`${baseURL}api/cart?action=getCart`);
        //     this.cart = JSON.stringify(carts.data.data);
            
        //     // presist product on change
        //     this.$watch('products', (val) => {
        //         localStorage.setItem('products', JSON.stringify(val));
        //     })

        //     const history = JSON.parse(localStorage.getItem('history'))
        //     this.history = history ?? [];

        //     // presist history on change
        //     this.$watch('history', (val) => {
        //         localStorage.setItem('history', JSON.stringify(val));
        //     })
        // },
        async getCart() {
            // axios(`${baseURL}api/cart?action=getCart`).then(response => {
            //     this.cart = response.data.data
            //     console.log(this.cart);
            // })
            // .catch(error => {

            // })
            $.ajax({
                url: `${baseURL}api/cart?action=getCart`,
                type: 'GET',
                success: function(response) {
                    this.cart = response.data;
                    console.log(this.cart.length);
                },
                error: function(data) {
                    Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
                },
                beforeSend: function() {
                    $('#cart').html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
                }
            });
            // const carts = await axios(`${baseURL}api/cart?action=getCart`);
            // let cart = carts.data.data;
            // this.cart = cart;
            // console.log(this.cart);
        },
        addToCart(product_id) {
            return axios.post(`${baseURL}api/order`, {
                action: 'addToCart',
                product: product_id,
                quantity: 1
            }).then((result) => {
                this.getCart()
            }).catch((err) => {
                
            });
        },
        deleteItemCart(item) {
            this.cart = this.cart.filter(i => i.key !== item.key)
        },
        get totalPriceInCart() {
            return this.cart.reduce((total, item) => {
                return total + (item.harga * item.qty)
            }, 0)
        },

        addProduct() {
            this.products.push({
                key: Math.random().toString(36).substr(2, 9),
                name: null,
                price: 0
            })
        },
        deleteItemProduct(item) {
            this.products = this.products.filter(i => i.key !== item.key)
        },
        handleChangeOptProduct(item, opt) {
            item.product = opt.product
        },
        getProductByKey(key) {
            return this.products.find(item => item.key === key) ?? {}
        },

        checkout() {
            this.history.push({
                key: Math.random().toString(36).substr(2, 9),
                date: new Date(),
                cart: this.cart
            })

            this.cart = []
        },

        reset() {
            localStorage.clear()
            this.init()
        }
    }
}
</script>