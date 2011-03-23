<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/sandbox/autoload.php";

// Configuration to simplify code below:

$auth_callback = create_function(
	'$username,$password',
	'return ( $username->raw() == "superadmin" && $password->raw() == "secret" );'
);

$static_urls = Prb::Ary( array( Prb::Str( '/public' ) ) );
$templates   = Prb::Str( join( DIRECTORY_SEPARATOR, array( dirname( __FILE__ ), 'templates' ) ) );

$env = Prack_ModPHP_Compat::singleton()->extractEnv( $_SERVER );
$env->set( 'sandbox.root'     , Prb::Str( dirname( __FILE__ ) ) );
$env->set( 'sandbox.templates', Prb::Str( join( DIRECTORY_SEPARATOR, array( $env->get( 'sandbox.root' )->raw(), 'templates' ) ) ) );

// Building the domain:

$domain = Prack_Builder::domain()
  ->using( 'Prack_ShowExceptions' )->build()
  ->using( 'Prack_Etag'           )->build()
  ->using( 'Prack_ContentType'    )->withArgs( Prb::Str( 'text/html' )                     )->build()
  ->using( 'Prack_Static'         )->withArgs( Prb::Hsh( array( 'urls' => $static_urls ) ) )->build()

  ->map( '/' )
    ->using( 'Sandbox_Showenv'     )->build()
    ->using( 'Sandbox_Showruntime' )->build()
    ->using( 'Prack_Runtime'       )->build()
    ->run( new Sandbox_Public() )

  ->map( '/admin' )
    ->using( 'Prack_Auth_Basic'    )->withArgs( Prb::Str( 'sandbox realm' ), $auth_callback )->build()
    ->using( 'Sandbox_Showenv'     )->build()
    ->using( 'Sandbox_Showruntime' )->build()
    ->using( 'Prack_Runtime'       )->build()
    ->run( new Sandbox_Admin() )

  ->map( '/thrower' )
    ->run( new Sandbox_Thrower() )

->toMiddlewareApp();

// Rendering the response:

Prack_ModPHP_Compat::singleton()->render( $domain->call( $env ) );

?>
