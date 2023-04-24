<?php

function reset_cart_session() {
    if(!session_id()) {
        session_start();
    }
    $_SESSION['cart'] = array();
}

add_action('wp', 'reset_cart_session', 1);

add_action('wp_ajax_add_product', 'add_product');
add_action('wp_ajax_nopriv_add_product', 'add_product');


function add_product() {
    session_start();

    if(isset($_POST['barcode'])) {
        $barcode = $_POST['barcode'];

        $args = array(
            'post_type' => 'produtos',
            'meta_query' => array(
                array(
                    'key' => 'codigo_de_barras',
                    'value' => $barcode,
                    'compare' => '=',
                )
            )
        );
        $query = new WP_Query($args);

        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();

                $price = floatval(str_replace(',', '.', get_field('preco')));
                $title = get_the_title();
                $barcode = get_field('codigo_de_barras');

                $product_found = false;
                $product_key = '';

                if(isset($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $key => $product) {
                        if($product['barcode'] == $barcode) {
                            $product_key = $key;
                            $product_found = true;
                            break;
                        }
                    }
                }

                if($product_found) {
                    $_SESSION['cart'][$product_key]['quantity']++;
                    $_SESSION['cart'][$product_key]['total_price'] = $_SESSION['cart'][$product_key]['quantity'] * $price;
                    $_SESSION['total_price'] = calculate_total_price();
                }
                
                if(!$product_found) {
                    $product = array(
                        'barcode' => $barcode,
                        'title' => $title,
                        'price' => $price,
                        'quantity' => 1,
                        'unity_price' => $price,
                        'total_price' => $price,
                    );
                    $_SESSION['cart'][] = $product;
                    $_SESSION['total_price'] = calculate_total_price();
                }
            }
            wp_reset_postdata();

            $products_in_cart = array();
            foreach($_SESSION['cart'] as $product) {
                $products_in_cart[] = array(
                    'barcode' => $product['barcode'],
                    'title' => $product['title'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'unity_price' => $price,
                    'total_price' => $product['total_price'],
                );
            }

            wp_send_json_success(array(
                'products' => $products_in_cart,
                'total_price' => calculate_total_price()
            ));
        }
        else {
            wp_send_json_error('Produto não encontrado');
        }
    }
}

function calculate_total_price() {
    $total_price = 0;
    foreach($_SESSION['cart'] as $product) {
        $total_price += $product['total_price'];
    }
    return $total_price;
}
?>