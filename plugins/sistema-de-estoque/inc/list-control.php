<?php
function generated_lists_page() {
    $pdf_directory = plugin_dir_path(__FILE__) . '../assets/documents/';

    // Verifica se o diretório existe
    if (!is_dir($pdf_directory)) {
        echo 'Nenhum PDF gerado encontrado.';
        return;
    }

    $pdf_files = scandir($pdf_directory);
    $pdf_files = array_diff($pdf_files, array('..', '.'));

    if (empty($pdf_files)) {
        echo 'Nenhum PDF gerado encontrado.';
        return;
    }
    ?>
    <h2>Listas Geradas</h2>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th>Arquivo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pdf_files as $pdf_file) { ?>
                <tr>
                    <td><?php echo $pdf_file; ?></td>
                    <td>
                        <a href="<?php echo plugin_dir_url(__FILE__) . '../assets/documents/' . $pdf_file; ?>" target="_blank" class="admin-btn">Visualizar</a>
                        <a href="<?php echo admin_url('admin-post.php?action=delete_pdf&file=' . $pdf_file); ?>" class="admin-btn">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php
}

add_action('admin_menu', 'add_generated_lists_submenu');
function add_generated_lists_submenu() {
    add_submenu_page(
        'edit.php?post_type=produtos',
        'Listas Geradas',
        'Listas Geradas',
        'manage_options',
        'generated_lists',
        'generated_lists_page'
    );
}

add_action('admin_post_delete_pdf', 'delete_generated_pdf');
function delete_generated_pdf() {
    if (!current_user_can('manage_options')) {
        wp_die('Acesso negado');
    }

    $pdf_directory = plugin_dir_path(__FILE__) . '../assets/documents/';

    $file = $_GET['file'] ?? '';
    if ($file && is_file($pdf_directory . $file)) {
        unlink($pdf_directory . $file);
    }

    wp_redirect(admin_url('admin.php?page=generated_lists'));
    exit;
}