<?php
add_action('wp_ajax_daily_report', 'daily_report');
add_action('wp_ajax_nopriv_daily_report', 'daily_report');
function daily_report(){
    $data_inicio = $_POST['init_date'];
    $data_fim = $_POST['final_date'];
    $args = array(
        'post_type' => 'vendas',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'valor_da_venda',
                'compare' => 'EXISTS',
            ),
        ),
        'date_query' => array(
            'after' => $data_inicio,
            'before' => $data_fim,
            'inclusive' => true,
        ),
        'fields' => 'ids',
        'posts_per_page' => -1,
    );
    
    $query = new WP_Query( $args );
    
    $total_value = 0;
    $quantidades_produtos = array();
    $produto_mais_vendido = null;
    $quantidade_mais_vendida = 0;
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $valor_da_venda = get_field( 'valor_da_venda', get_the_ID() );
            $total_value += (float) $valor_da_venda;
            $produtos = get_field('produtos_da_venda' , get_the_ID());

            foreach($produtos as $produto){
                $quantidade = $produto['quantity'];
                if (isset($quantidades_produtos[$produto['barcode']])) {
                    $quantidades_produtos[$produto['barcode']] += $quantidade;
                }else {
                    $quantidades_produtos[$produto['barcode']] = $quantidade;
                }

                foreach($quantidades_produtos as $produto['barcode'] => $quantidade){
                    if ($quantidade > $quantidade_mais_vendida) {
                        $quantidade_mais_vendida = $quantidade;
                        $produto_mais_vendido = $produto;
                    }
                }
            }
        }
    }

    wp_send_json_success(array(
        'date' => $data_inicio,
        'valor_da_venda' => $total_value,
        'produto_mais_vendido' => $produto_mais_vendido,
        'quantidade_mais_vendida' => $quantidade_mais_vendida,
    ));
    
    wp_reset_postdata();
}

?>