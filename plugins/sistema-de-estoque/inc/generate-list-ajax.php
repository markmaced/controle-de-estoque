<?php

use Fpdf\Fpdf;
add_action('wp_ajax_generate_list_ceasa', 'generate_list_ceasa');
add_action('wp_ajax_nopriv_generate_list_ceasa', 'generate_list_ceasa');
function generate_list_ceasa() {
    require(plugin_dir_path(__FILE__) . '../vendor/fpdf/fpdf/src/Fpdf/Fpdf.php');

    // Diretório de destino para salvar o arquivo PDF
    $destination_dir = plugin_dir_path(__FILE__) . '../assets/documents/';

    ob_clean();

    $pdf = new Fpdf();

    $pdf->SetTitle('Lista do Ceasa');
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(95, 10, utf8_decode('Nome do produto'), 1);
    $pdf->Cell(95, 10, utf8_decode('Estoque'), 1);
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

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product_name = get_the_title();
            $product_stock = get_field('estoque');

            $pdf->Cell(95, 10, utf8_decode($product_name), 1);
            $pdf->Cell(95, 10, utf8_decode($product_stock), 1);
            $pdf->Ln();
        }
    }

    wp_reset_postdata();

    $filename = $destination_dir . 'lista-ceasa-' . date('d-m-Y') . '.pdf';
    $pdf->Output('F', $filename);

    $pdf_url = plugin_dir_url(__FILE__) . '../assets/documents/' . basename($filename);

    echo json_encode(array('pdfUrl' => $pdf_url));
    exit;
}

?>