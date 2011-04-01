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

  ->using( 'Prack_ShowExceptions'  )->withArgs( $also = E_ALL & ~E_NOTICE | E_STRICT )->push()
  ->using( 'Prack_ContentLength'   )->push()
  ->using( 'Livesite_ShowHeaders'  )->push()
  ->using( 'Prack_ContentType'     )->withArgs( 'text/html' )->push()
  ->using( 'Livesite_ShowRuntimes' )->withArgs( array( 'Total', 'Public-Site', 'Admin-Site' ) )->push()
  ->using( 'Prack_Runtime'         )->withArgs( 'Total' )->push()
  ->using( 'Prack_Config'          )->withCallback( 'onConfigure' )->push()
  ->using( 'Prack_Etag'            )->push()

  ->map( '/livesite' )

    ->map( '/' )
      ->using( 'Prack_Runtime' )->withArgs( 'Public-Site' )->push()
      ->run( new Livesite_Public() )
    ->endMap()

    ->map( '/static' )
      ->run( new Prack_Directory( './public' ) )
    ->endMap()

    ->map( '/admin' )
      ->using( 'Prack_Runtime'      )->withArgs( 'Admin-Site' )->push()
      ->using( 'Livesite_Trollface' )->push()
      ->using( 'Prack_Auth_Basic'   )->withArgs( 'Livesite Admin Area' )->andCallback( 'onAuthBasicAuthenticate' )->push()
      ->using( 'Livesite_ShowEnv'   )->push()
      ->run( new Livesite_Admin() )
    ->endMap()

    ->map( '/thrower' )
      ->run( new Livesite_Thrower() )
    ->endMap()

  ->endMap();

// Handling the response:

$modphp_compat = Prack_ModPHP_Compat::singleton();
$env           = $modphp_compat->extractEnv( $_SERVER );

$modphp_compat->render( $domain->call( $env ) );