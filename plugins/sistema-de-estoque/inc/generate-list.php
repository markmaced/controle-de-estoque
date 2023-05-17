<?php

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

function generate_list_page(){
    $args = array(
        'post_type' => 'produtos',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'estoque',
                'value' => '3',
                'compare' => '<',
                'type' => 'NUMERIC'
            )
        )
    );
    $products_query = new WP_Query($args);

    echo '<h2>Gerar lista do ceasa</h2>';

    if($products_query->have_posts()) {?>
        <table class="wp-list-table widefat striped">       
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Código de Barras</th>
                    <th>Estoque</th>
                </tr>
            </thead>
        <?php while ($products_query->have_posts()) {
                $products_query->the_post();?>
                <tbody>
                    <tr style="border-bottom: 1px solid #c3c4c7;">
                        <td><?php echo the_title()?></td>
                        <td><?php echo get_field('codigo_de_barras' , get_the_ID())?></td>
                        <td><?php echo get_field('estoque' , get_the_ID())?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    <?php }

    ?>
    <div class="wrap">
		<button type="button" class="admin-btn" id="download">Baixar lista</button>
	</div>
    <?php
}
?>