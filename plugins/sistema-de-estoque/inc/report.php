<?php
function adicionar_submenu_relatorios() {
    add_submenu_page(
        'edit.php?post_type=vendas',
        'Relatórios',
        'Relatórios',
        'manage_options',
        'relatorios',
        'exibir_relatorios'
    );
}
add_action('admin_menu', 'adicionar_submenu_relatorios');


function exibir_relatorios() { ?>
    <div class="wrap">
        <h2 class="wp-heading-inline">Relatórios de Vendas</h2>
        <hr class="wp-header-end">
        <form method="POST">
            <label for="date">Data do relatório</label>
            <input type="date" id="date">
            <button type="button" class="button-date">Buscar</button>
        </form>
        <h2>Relatório Diário</h2>
        <p class="daily-total"></p>
    </div>
    <?php
}
?>