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


function exibir_relatorios() {

    $data_inicio = date('Y-m-d');
    $data_fim = date('Y-m-d', strtotime($data_inicio . ' +1 day'));

    $args = array(
        'post_type' => 'vendas',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'valor_da_venda',
                'value' => 0,
                'compare' => '>'
            ),
            array(
                'key' => 'data',
                'value' => array($data_inicio, $data_fim),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            )
        )
    );
    $relatorio_vendas = new WP_Query($args);

    $total_vendas = 0;
    while ($relatorio_vendas->have_posts()) {
        $relatorio_vendas->the_post();
        $total_vendas += get_field('valor_da_venda');
    }

    $args = array(
        'post_type' => 'vendas',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'valor_da_venda',
                'value' => 0,
                'compare' => '>'
            ),
            array(
                'key' => 'data',
                'value' => array($data_inicio, $data_fim),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            )
        )
    );
    $relatorio_produtos_vendidos = new WP_Query($args);

    $produtos_vendidos = array();

    while ($relatorio_produtos_vendidos->have_posts()) {
        $relatorio_produtos_vendidos->the_post();
        $produtos = get_field('produtos_da_venda', get_the_ID());
        var_dump($produtos);
        foreach ($produtos as $produto) {
            $codigo_de_barras = $produto['codigo_de_barras'];
            $nome = $produto['nome'];
            $quantidade = $produto['quantidade'];
            if (isset($produtos_vendidos[$codigo_de_barras])) {
                $produtos_vendidos[$codigo_de_barras]['quantidade'] += $quantidade;
            } else {
                $produtos_vendidos[$codigo_de_barras] = array(
                    'nome' => $nome,
                    'quantidade' => $quantidade
                );
            }
        }
    }

    uasort($produtos_vendidos, function ($a, $b) {
        return $b['quantidade'] - $a['quantidade'];
    });

    $produto_mais_vendido = reset($produtos_vendidos);

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline">Relatórios de Vendas</h1>
        <hr class="wp-header-end">
        <h2>Relatório Diário</h2>
        <p>Data: <?php echo date('d/m/Y'); ?></p>
        <p>Valor Total das Vendas: R$<?php echo number_format($total_vendas, 2, ',', '.'); ?></p>
        <p>Produto mais vendido: <?php echo $produto_mais_vendido['nome']; ?> (<?php echo $produto_mais_vendido['quantidade']; ?> unidades)</p>

        <h2>Relatório de Produtos Vendidos</h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Código de Barras</th>
                    <th>Quantidade Vendida</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos_vendidos as $produto) : ?>
                    <tr>
                        <td><?php echo $produto['nome']; ?></td>
                        <td><?php echo $produto['codigo_de_barras']; ?></td>
                        <td><?php echo $produto['quantidade']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>