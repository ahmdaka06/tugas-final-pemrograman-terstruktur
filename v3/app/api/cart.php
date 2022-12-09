<?php
require_once '../../init.php';
if (isMethod('post') AND isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'addToCart':
            $product_id = $_POST['product'];
            $quantity = $_POST['quantity'];

            $products = $config['products'];
            $product = null;
            foreach ($products as $key => $value) {
                if ($value['id'] == $product_id) {
                    $product = $value;
                    break;
                }
            }
            if (!is_array($product)) {
                return response([
                    'status' => false,
                    'msg' => 'Produk tidak tersedia!.'
                ]);
            } else if (!is_numeric($quantity) OR $quantity < 1) {
                return response([
                    'status' => false,
                    'msg' => 'Jumlah pemesanan tidak sesuai!.'
                ]);
            } else {
                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                if (isset($cart) && is_array($cart)) {
                    if (array_key_exists($product['id'], $cart)) {
                        $cart[$product['id']]['quantity'] = $cart[$product['id']]['quantity'] + $quantity;
                    } else {
                        $cart[$product['id']] = [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'quantity' => $quantity,
                            'price' => $product['price'],
                        ];
                    }
                }
                $_SESSION['cart'] = $cart;
                return response([
                    'status' => true,
                    'msg' => 'Berhasil menambahkan produk!.'
                ]);
            }
            break;
    
        case 'updateCartOne':
            $product = $_POST['product'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if($product && $quantity && isset($_SESSION['cart'][$product])){
                $_SESSION['cart'][$product]['quantity'] = $quantity;
                return response([
                    'status' => true,
                    'msg' => 'Berhasil mengubah keranjang!.'
                ]);
            } else {
                return response([
                    'status' => false,
                    'msg' => 'Gagal mengubah keranjang!.'
                ]);
            }
            break;
        case 'updateCartAll':
            $product = $_POST['product'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if(is_array($product) && is_array($quantity)){
                $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                foreach ($product as $key => $value) {
                    $_SESSION['cart'][$key]['quantity'] = $quantity[$key];
                }
                return response([
                    'status' => true,
                    'msg' => 'Berhasil mengubah keranjang!.'
                ]);
            } else {
                return response([
                    'status' => false,
                    'msg' => 'Gagal mengubah keranjang!.'
                ]);
            }
            break;
        case 'removeCart':
            if (isset($_POST['product']) AND is_numeric($_POST['product'])) {
                if (isset($_SESSION['cart'][$_POST['product']]) AND is_array($_SESSION['cart'][$_POST['product']])) {
                    unset($_SESSION['cart'][$_POST['product']]);
                    return response([
                        'status' => true,
                        'msg' => 'Produk pada keranjang berhasil dihapus!.'
                    ]);
                }
                return response([
                    'status' => false,
                    'msg' => 'Produk tidak sesuai!.'
                ]);
            }
            return response([
                'status' => false,
                'msg' => 'Produk pada keranjang tidak sesuai!.'
            ]);
            break;
            
        case 'checkoutCart':
            $generateOrderID = time(); // generate order id
            $total_order = 0; // set total order
            $carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            if (!empty($carts)) {
                foreach ($carts as $cart) {
                    $total_order += $cart['price'] * $cart['quantity'];
                }
            }
            // set history order
            $setOrderHistory = [
                'totalOrderAmount' => $total_order,
                'discountAmount' => get_amount_discount($total_order),
                'discountTotal' => get_discount($total_order) . '%',
                'items' => $carts,
                'created_at' => date('Y-m-d')
            ];
            $orderHistory = isset($_SESSION['orderHistory']) ? $_SESSION['orderHistory'] : [];
            $orderHistory[$generateOrderID] = $setOrderHistory;
            $_SESSION['orderHistory'] = $orderHistory;
            unset($_SESSION['cart']);
            return response([
                'status' => true,
                'msg' => 'Pesanan telah berhasil di lakukan!.',
                'orderID' => $generateOrderID,
                'data' => $setOrderHistory
            ]);
            break;
        default:
            http_response_code(404);
            break;
    }
} else {
    $request = $_GET;
    return response([
        'status' => true,
        'data' => render(_DIR_PATH_('app/view/cart/index.php'))
    ]);
}
