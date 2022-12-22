<?php
if ($_GET) {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'empty_cart':
                unset($_SESSION);
                flashdata(['alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Keranjang berhasil di kosongkan!.']);
                exit(redirect(url()));
                break;
            case 'delete_cart':
                if (isset($_GET['product_id']) AND is_numeric($_GET['product_id'])) {
                    if (isset($_SESSION['cart'][$_GET['product_id']]) AND is_array($_SESSION['cart'][$_GET['product_id']])) {
                        unset($_SESSION['cart'][$_GET['product_id']]);
                        flashdata(['alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesanan berhasil dihapus!.']);
                        exit(redirect(url()));
                    }
                    flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Produk tidak sesuai!.']);
                    exit(redirect(url()));
                }
                flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Produk pada keranja tidak sesuai!.']);
                exit(redirect(url()));
                break;
            default:
                flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Aksi tidak ditemukan!.']);
                exit(redirect(url()));
                break;
        }
    }
}

if (isset($_POST['order'])) {
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
        flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Produk tidak tersedia!.']);
    } else if (!is_numeric($quantity) OR $quantity < 1) {
        flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Jumlah pemesanan tidak sesuai!.']);
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
        flashdata(['alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Berhasil menambahkan produk!.']);
        exit(redirect(url()));
    }
}
if (isset($_POST['update'])) {
    $product = $_POST['cart_product_id'] ?? null;
    $quantity = $_POST['cart_quantity'] ?? null;

    if(is_array($product) && is_array($quantity)){
        $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        foreach ($product as $key => $value) {
            $_SESSION['cart'][$key]['quantity'] = $quantity[$key];
        }
        flashdata(['alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Berhasil mengubah keranjang!.']);
        exit(redirect(url()));
    } else {
        flashdata(['alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal mengubah keranjang!.']);
        exit(redirect(url()));
    }
}