<?php
add_action('wp_ajax_create_order', 'create_order');
add_action('wp_ajax_nopriv_create_order', 'create_order');
function create_order(){

    if(isset($_POST['products'])) {
        $products = $_POST['products'];
    }
    if(isset($_POST['total'])) {
        $total = $_POST['total'];
    }
    $post_data = array(
        'post_title'   => date('d-m-Y'), 
        'post_status'  => 'publish', 
        'post_type'    => 'vendas', 
    );
    
    $post_id = wp_insert_post( $post_data );
    
    update_field( 'valor_da_venda', $total, $post_id );
    
    foreach ( $products as $product ) {
        $barcode = $product['barcode'];
        $title = $product['title'];
        $price = $product['price'];
        $quantity = $product['quantity'];
        $unity_price = $product['unity_price'];
        $total_price = $product['total_price'];

        add_row( 'produtos_da_venda', array(
            'barcode' => $barcode,
            'title' => $title,
            'price' => $price,
            'quantity' => $quantity,
            'unity_price' => $unity_price,
            'total_price' => $total_price,
        ), $post_id );

        $product_id = null;
        $products_query = new WP_Query(array(
            'post_type' => 'produtos',
            'meta_query' => array(
                array(
                    'key' => 'codigo_de_barras',
                    'value' => $barcode,
                    'compare' => '=',
                ),
            ),
        ));
        
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                $product_id = get_the_ID();
            }
            wp_reset_postdata();
        }
        
        if ($product_id) {
            $stock = get_field('estoque', $product_id);
            if ($stock >= $quantity) {
                $new_stock = $stock - $quantity;
                update_field('estoque', $new_stock, $product_id);
            } else {
                
            }
        } else {
            
        }
    }
    wp_send_json_success(array(
        'venda' => $post_data,
    ));
}