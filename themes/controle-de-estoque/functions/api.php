<?php

//////////////////////////////////
//////////  USER REGISTER  ///////
//////////////////////////////////

add_action('rest_api_init', function () {
	register_rest_route( 'stockControl', '/profile/register',array(
        'methods'  => 'POST',
        'callback' => 'new_register_user',
    ));
});

function new_register_user(){

    $user_email = $_POST['email'];
    $user_name = $_POST['name'];
    $user_password = $_POST['password'];
    $user_phone = $_POST['phone'];

    $firstName = explode("\t", $user_name);

    if(get_users(array('meta_key' => 'telefone', 'meta_value' => $user_phone)) ) {
        return 'Já existe um usuário cadastrado com esse telefone, faça login ou recupere seua conta.';
    };

    if( null == email_exists( $user_email ) ) {

        $user_id = wp_create_user( $user_name, $user_password, $user_email );
      
        // Set the nickname
        wp_update_user(
          array(
            'ID'           =>  $user_id,
            'nickname'     =>  $user_email,
            'display_name' =>  $user_name,
            'firstname'    =>  $firstName
          )
        );
      
        // Set the role
        // $user = new WP_User( $user_id );
        // $user->set_role( 'custom_role' );

        update_field('telefone', $user_phone, 'user_'. $user->id);
      
        return display_user_json($user_id);
      
    }else{
        return 'Este email já existe, tente acessar sua conta com email ou número de telefone';
    }
}
 

//////////////////////////////////////////
//////////  GET USER BY PHONE ////////////
/////////////////////////////////////////

add_action('rest_api_init', function () {
	register_rest_route( 'stockControl', '/profile/getUser',array(
        'methods'  => 'POST',
        'callback' => 'getUserByPhone',
    ));
});

function getUserByPhone(){

    $user_phone = $_POST['phone'];
    $user = get_users(array('meta_key' => 'telefone', 'meta_value' => $user_phone));

    if($user){
        return display_user_json($user[0]->data->ID);
    }else{
        echo '{erro: "Nenhum cadastro foi encontrado com este telefone, verifique as informações."}';
    }

    die();
}
  

?>