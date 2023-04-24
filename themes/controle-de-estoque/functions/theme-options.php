<?php 
/**
 * General options to config
 *
 * @package Marcos Macedo
 */

 /* CPT */


function cpt_produtos() {

    $labels = array(
        'name' => 'Produtos',
        'singular_name' => 'Produto',
        'add_new' => 'Adicionar produto',
        'add_new_item' => 'Adicionar produto',
        'edit_item' => 'Editar produto',
        'new_item' => 'Novo produto',
        'all_items' => 'Todos os Produtos',
        'view_item' => 'Ver produto',
        'search_items' => 'Buscar Produto',
        'not_found' =>  'Nenhum produto encontrado',
        'not_found_in_trash' => 'Nenhum produto encontrado', 
        'parent_item_colon' => '',
        'menu_name' => 'Produtos',
    );
    
    // register post type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'produtos'),
        'query_var' => true,
        'menu_icon' => 'dashicons-tag',
        'supports' => array(
            'title',
            'thumbnail',
        )
    );
    register_post_type( 'produtos', $args );

}
add_action( 'init', 'cpt_produtos' );

function cpt_vendas() {

    $labels = array(
        'name' => 'Vendas',
        'singular_name' => 'Venda',
        'add_new' => 'Adicionar venda',
        'add_new_item' => 'Adicionar venda',
        'edit_item' => 'Editar venda',
        'new_item' => 'Nova venda',
        'all_items' => 'Todas as vendas',
        'view_item' => 'Ver venda',
        'search_items' => 'Buscar venda',
        'not_found' =>  'Nenhuma venda encontrada',
        'not_found_in_trash' => 'Nenhuma venda encontrada', 
        'parent_item_colon' => '',
        'menu_name' => 'Vendas',
    );
    
    // register post type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'vendas'),
        'query_var' => true,
        'menu_icon' => 'dashicons-tag',
        'supports' => array(
            'title',
            'thumbnail',
        )
    );
    register_post_type( 'vendas', $args );

}
add_action( 'init', 'cpt_vendas' );
    

/*****************************************************/

