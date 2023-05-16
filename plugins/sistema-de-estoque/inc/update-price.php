<?php

add_action('wp_ajax_price_per_weight', 'price_per_weight');
add_action('wp_ajax_nopriv_price_per_weight', 'price_per_weight');
function price_per_weight() {
    session_start();

    if (isset($_POST['productIndex']) && isset($_POST['total_price'])) {
        $productIndex = $_POST['productIndex'];
        $total_price = floatval(str_replace(',', '.', $_POST['total_price']));

        if (isset($_SESSION['cart'][$productIndex])) {
            $_SESSION['cart'][$productIndex]['total_price'] = $total_price;
            $_SESSION['total_price'] = calculate_total_price();
        }

        wp_send_json_success(array(
            'products' => $_SESSION['cart'],
            'total_price' => $_SESSION['total_price']
        ));
    }
}

?>