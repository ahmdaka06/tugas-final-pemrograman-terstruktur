<?php


require_once '../../init.php';
cors();
header('Content-Type: application/json');
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
        
        case 'updateCart':
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
            
        default:
            http_response_code(404);
            break;
    }
} else {
    $request = $_GET;
    return response([
        'status' => true,
        'data' => render('../view/cart/index.php')
    ]);
}
