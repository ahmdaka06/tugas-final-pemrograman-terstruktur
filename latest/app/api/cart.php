<?php
require_once '../../init.php';
header('Content-type: application/json');
cors();
$request = json_decode(file_get_contents('php://input'), true);
if (isMethod('post') AND isset($request['action'])) {
    switch ($request['action']) {
        case 'addToCart':
            $product_id = $request['product'];
            $quantity = $request['quantity'];

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
            $product = $request['product'] ?? null;
            $type = $request['type'];
            if (!in_array($type, ['increment', 'decrement'])) {
                return response([
                    'status' => false,
                    'msg' => 'Aksi mengubah keranjang tidak ditemukan!.'
                ]);
            }
            if($product && isset($_SESSION['cart'][$product])){
                if ($type == 'increment') {
                    $_SESSION['cart'][$product]['quantity'] = $_SESSION['cart'][$product]['quantity'] + 1;
                } elseif ($type == 'decrement') {
                    $_SESSION['cart'][$product]['quantity'] = ($_SESSION['cart'][$product]['quantity'] > 0) 
                        ? $_SESSION['cart'][$product]['quantity'] - 1
                        : 
                        1;
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
        case 'updateCartAll':
            $product = $request['product'] ?? null;
            $quantity = $request['quantity'] ?? null;

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
            if (isset($request['product']) AND is_numeric($request['product'])) {
                if (isset($_SESSION['cart'][$request['product']]) AND is_array($_SESSION['cart'][$request['product']])) {
                    unset($_SESSION['cart'][$request['product']]);
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
                'uid' => time(),
                'totalOrderAmount' => $total_order,
                'discountAmount' => get_amount_discount($total_order),
                'discountTotal' => get_discount($total_order) . '%',
                'items' => $carts,
                'created_at' => format_datetime(date('Y-m-d H:i:s'))
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
    $carts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $priceSubTotal = 0;
    foreach ($carts as $cart) {
        $priceSubTotal += $cart['price'] * $cart['quantity'];
    }
    return response([
        'status' => true,
        'data' => [
            'items' => $carts,
            'totalItems' => count($carts),
            'discount' => get_discount($priceSubTotal),
            'priceSubTotal' => $priceSubTotal,
            'discountPrice' => get_amount_discount($priceSubTotal),
            'lastPrice' => $priceSubTotal - get_amount_discount($priceSubTotal)
        ]
    ]);
}
