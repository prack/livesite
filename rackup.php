<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/sandbox/autoload.php";

$auth_callback = create_function(
	'$username,$password',
	'return ( $username->raw() == "admin" && $password->raw() == "secret" );'
);

$domain = Prack_Builder::domain()

  ->using( 'Prack_Runtime'        )->build()
  ->using( 'Sandbox_Showenv'      )->build()
  ->using( 'Prack_ShowExceptions' )->build()
  ->using( 'Prack_ContentType'    )->withArgs( Prb::_String( 'text/html' ) )->build()

  ->map( '/admin' )

    ->using( 'Prack_Auth_Basic' )->withArgs( Prb::_String( 'sandbox realm' ), $auth_callback )->build()
    ->run( new Sandbox_Admin() )

  ->map( '/' )

    ->run( new Sandbox_Public() )

->toMiddlewareApp();

$compat = Prack_ModPHP_Compat::singleton();
$compat->render( $domain->call( $compat->rackEnvFrom( $_SERVER ) ) );

?>
