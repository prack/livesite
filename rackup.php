<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/sandbox/autoload.php";

// Middleware app callbacks:

function onConfigure( $env )
{
	$env->set( 'sandbox.root'     , Prb::Str( dirname( __FILE__ ) ) );
	$env->set( 'sandbox.templates', Prb::Str( join( DIRECTORY_SEPARATOR, array( $env->get( 'sandbox.root' )->raw(), 'templates' ) ) ) );
}

function onAuthBasicAuthenticate( $username, $password )
{
	return $username->raw() == 'superadmin' && $password->raw() == 'secret';
}

// Building the domain:

$static_urls = Prb::Ary( array( Prb::Str( '/public' ) ) );

$domain = Prack_Builder::domain()

  ->using( 'Prack_Config'         )->withArgs( 'onConfigure' )->build()
  ->using( 'Prack_Runtime'        )->withArgs( 'Total' )->build()
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
    ->using( 'Prack_Auth_Basic'    )->withArgs( Prb::Str( 'sandbox admin site' ), 'onAuthBasicAuthenticate' )->build()
    ->using( 'Sandbox_Showenv'     )->build()
    ->using( 'Sandbox_Showruntime' )->build()
    ->using( 'Prack_Runtime'       )->build()
    ->run( new Sandbox_Admin() )

  ->map( '/thrower' )
    ->run( new Sandbox_Thrower() )

->toMiddlewareApp();

// Handling the response:

$modphp_compat = Prack_ModPHP_Compat::singleton();
$modphp_compat->render(
  $domain->call( $modphp_compat->extractEnv( $_SERVER ) )
);