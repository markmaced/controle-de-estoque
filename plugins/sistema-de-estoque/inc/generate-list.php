<?php

use Fpdf\Fpdf;

function add_submenu_generate_list() {
    add_submenu_page(
        'edit.php?post_type=produtos',
        'Gerar lista',
        'Gerar lista',
        'manage_options',
        'generate_list',
        'generate_list_page'
    );
}
add_action('admin_menu', 'add_submenu_generate_list');

function generate_list_page() {
    require( plugin_dir_path( __FILE__ ) . '../vendor/fpdf/fpdf/src/Fpdf/Fpdf.php' );
    
    ob_clean();

    $pdf = new Fpdf();

    $pdf->SetTitle('Lista do Ceasa');
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(40, 10, 'Nome do produto', 1);
    $pdf->Cell(40, 10, 'Estoque', 1);
    $pdf->Ln();

    $args = array(
        'post_type' => 'produtos',
        'meta_query' => array(
            array(
                'key' => 'estoque',
                'value' => '3',
                'compare' => '<',
                'type' => 'NUMERIC'
            )
        ),
        'posts_per_page' => -1
    );
    $products_query = new WP_Query($args);

    if($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_name = get_the_title();
            $product_stock = get_field('estoque');
            $pdf->Cell(40, 10, $product_name, 1);
            $pdf->Cell(40, 10, $product_stock, 1);
            $pdf->Ln();
        }
    }else{
    }

    wp_reset_postdata();

    $filename = 'lista-ceasa-'.date('d-m-Y').'.pdf';
    $pdf->Output('D', $filename , true);
    exit;
}
?>