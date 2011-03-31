<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/sandbox/autoload.php";

// Middleware app callbacks:

function onConfigure( &$env )
{
	$env[ 'sandbox.root'      ] = dirname( __FILE__ );
	$env[ 'sandbox.templates' ] = join( DIRECTORY_SEPARATOR, array( $env[ 'sandbox.root' ], 'templates' ) );
}

function onAuthBasicAuthenticate( $username, $password )
{
	return ( $password == 'secret' );
}

$domain = Prack_Builder::domain()

  ->using( 'Prack_ContentLength'  )->build()
  ->using( 'Sandbox_ShowHeaders'  )->build()
  ->using( 'Prack_ContentType'    )->withArgs( 'text/html' )->build()
  ->using( 'Sandbox_ShowRuntimes' )->withArgs( array( 'Total', 'Public-Site', 'Admin-Site' ) )->build()
  ->using( 'Prack_Runtime'        )->withArgs( 'Total' )->build()
  ->using( 'Prack_Config'         )->withCallback( 'onConfigure' )->build()
  ->using( 'Prack_ShowExceptions' )->build()
  ->using( 'Prack_Etag'           )->build()

  ->map( '/' )
    ->using( 'Prack_Runtime' )->withArgs( 'Public-Site' )->build()
    ->run( new Sandbox_Public() )

  ->map( '/static' )
    ->run( new Prack_Directory( './public' ) )

  ->map( '/admin' )
    ->using( 'Prack_Auth_Basic'    )->withArgs( 'sandbox admin site' )->andCallback( 'onAuthBasicAuthenticate' )->build()
    ->using( 'Sandbox_ShowEnv'     )->build()
    ->using( 'Prack_Runtime'       )->withArgs( 'Admin-Site' )->build()
    ->run( new Sandbox_Admin() )

  ->map( '/thrower' )
    ->run( new Sandbox_Thrower() )

->toMiddlewareApp();

// Handling the response:

$modphp_compat = Prack_ModPHP_Compat::singleton();
$env           = $modphp_compat->extractEnv( $_SERVER );

$modphp_compat->render( $domain->call( $env ) );