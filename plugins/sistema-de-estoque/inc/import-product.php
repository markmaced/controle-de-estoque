<?php
function add_product_submenu() {
	add_submenu_page(
		'edit.php?post_type=produtos',
		'Importar produtos',
		'Importar produtos',
		'manage_options',
		'importar-produtos',
		'import_products_page'
	);
}
add_action( 'admin_menu', 'add_product_submenu' );

function import_products_page() {
	?>
	<div class="wrap">
		<h1>Importar produtos</h1>
		<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="import_products">
            <label for="csv_file">Arquivo CSV:</label>
            <input type="file" name="csv_file" id="csv_file">
            <?php wp_nonce_field( 'import_products_nonce', 'import_products_nonce' ); ?>
            <input type="submit" name="submit" value="Importar">
        </form>
	</div>
	<?php
}

function process_import() {
	if ( isset( $_POST['submit'] ) && isset( $_FILES['csv_file'] ) ) {
		if ( $_FILES['csv_file']['error'] == UPLOAD_ERR_OK && $_FILES['csv_file']['type'] == 'text/csv' ) {
			$file = fopen( $_FILES['csv_file']['tmp_name'], 'r' );
			while ( ( $data = fgetcsv( $file ) ) !== FALSE ) {
				$product_name = $data[0];
				$price = $data[1];
				$stock = $data[2];
                $code = $data[3];
				$price_per_kg = $data[4];
				$args = array(
					'post_type' => 'produtos',
					'post_title' => $product_name,
					'post_status' => 'publish',
				);	
				$existing_products = get_posts( $args );
				if ( ! empty( $existing_products ) ) {
					foreach ( $existing_products as $existing_product ) {
						$product_id = $existing_product->ID;
						$product_code = get_field( 'codigo_de_barras', $product_id );
						if ( $product_code == $code ) {
							update_field( 'preco', $price, $product_id );
							update_field( 'estoque', $stock, $product_id );
							update_field( 'codigo_de_barras', $code, $product_id );
							update_field( 'preco_por_kg', $price_per_kg, $product_id );
							continue 2;
						}
					}
				}
				$new_post = array(
					'post_title' => $product_name,
					'post_status' => 'publish',
					'post_type' => 'produtos',
				);
				$product_id = wp_insert_post( $new_post );
				update_field( 'preco', $price, $product_id );
				update_field( 'estoque', $stock, $product_id );
				update_field( 'codigo_de_barras', $code, $product_id );
				update_field( 'preco_por_kg', $price_per_kg, $product_id );
			}
			fclose( $file );
		}
	}
}

add_action( 'admin_post_import_products', 'process_import' );

function import_products_capability( $capability ) {
	return 'manage_options';
}
add_filter( 'option_page_capability_importar-produtos', 'import_products_capability' );

?>