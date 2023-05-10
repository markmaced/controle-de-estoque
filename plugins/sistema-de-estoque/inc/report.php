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
        </form>
        <h2>Relatório Diário</h2>
        <p class="daily-total"></p>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Código de Barras</th>
                    <th>Quantidade Vendida</th>
                    <th>Valor total vendido</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="nome-produto-daily"></td>
                    <td class="barcode-produto-daily"></td>
                    <td class="quantity-produto-daily"></td>
                    <td class="total-produto-daily"></td>
                </tr>
            </tbody>
        </table>
        <h2>Relatório semanal</h2>
        <p class="week-total"></p>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Código de Barras</th>
                    <th>Quantidade Vendida</th>
                    <th>Valor total vendido</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="nome-produto-week"></td>
                    <td class="barcode-produto-week"></td>
                    <td class="quantity-produto-week"></td>
                    <td class="total-produto-week"></td>
                </tr>
            </tbody>
        </table>
        <h2>Relatório Mensal</h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Código de Barras</th>
                    <th>Quantidade Vendida</th>
                    <th>Valor total vendido</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="nome-produto-month"></td>
                    <td class="barcode-produto-month"></td>
                    <td class="quantity-produto-month"></td>
                    <td class="total-produto-month"></td>
                </tr>
            </tbody>
        </table>
        <p class="month-total"></p>
    </div>
    <?php
}
?>