<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/livesite/autoload.php";

// Middleware app callbacks:

function onConfigure( &$env )
{
	$env[ 'livesite.root'      ] = dirname( __FILE__ );
	$env[ 'livesite.templates' ] = join( DIRECTORY_SEPARATOR, array( $env[ 'livesite.root' ], 'templates' ) );
}

function onAuthBasicAuthenticate( $username, $password )
{
	return ( $password == 'secret' );
}

$domain = Prack_Builder::domain()

  ->using( 'Prack_ContentLength'   )->build()
  ->using( 'Livesite_ShowHeaders'  )->build()
  ->using( 'Prack_ContentType'     )->withArgs( 'text/html' )->build()
  ->using( 'Livesite_ShowRuntimes' )->withArgs( array( 'Total', 'Public-Site', 'Admin-Site' ) )->build()
  ->using( 'Prack_Runtime'         )->withArgs( 'Total' )->build()
  ->using( 'Prack_Config'          )->withCallback( 'onConfigure' )->build()
  ->using( 'Prack_ShowExceptions'  )->build()
  ->using( 'Prack_Etag'            )->build()

  ->map( '/' )
    ->using( 'Prack_Runtime' )->withArgs( 'Public-Site' )->build()
    ->run( new Livesite_Public() )

  ->map( '/static' )
    ->run( new Prack_Directory( './public' ) )

  ->map( '/admin' )
    ->using( 'Prack_Runtime'      )->withArgs( 'Admin-Site' )->build()
    ->using( 'Livesite_Trollface' )->build()
    ->using( 'Prack_Auth_Basic'   )->withArgs( 'Livesite Admin Area' )->andCallback( 'onAuthBasicAuthenticate' )->build()
    ->using( 'Livesite_ShowEnv'   )->build()
    ->run( new Livesite_Admin() )

  ->map( '/thrower' )
    ->run( new Livesite_Thrower() )

->toMiddlewareApp();

// Handling the response:

$modphp_compat = Prack_ModPHP_Compat::singleton();
$env           = $modphp_compat->extractEnv( $_SERVER );

$modphp_compat->render( $domain->call( $env ) );