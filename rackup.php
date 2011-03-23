<?php

include "lib/php-rack/autoload.php";
include "lib/php-rb/autoload.php";
include "lib/sandbox/autoload.php";

$auth_callback = create_function(
	'$username,$password',
	'return ( $username->raw() == "superadmin" && $password->raw() == "secret" );'
);

$public    = Prb::Str( join( DIRECTORY_SEPARATOR, array( dirname( __FILE__ ), 'public'    ) ) );
$templates = Prb::Str( join( DIRECTORY_SEPARATOR, array( dirname( __FILE__ ), 'templates' ) ) );

$domain = Prack_Builder::domain()
  ->using( 'Prack_Runtime'        )->build()
  ->using( 'Prack_ShowExceptions' )->build()
  ->using( 'Prack_Etag'           )->build()
  ->using( 'Prack_ContentType'    )->withArgs( Prb::Str( 'text/html' ) )->build()

  ->map( '/' )
    ->using( 'Sandbox_Showenv' )->build()
    ->run( new Sandbox_Public() )

  ->map( '/public' )
    ->run( new Prack_File( $public ) )

  ->map( '/admin' )
    ->using( 'Prack_Auth_Basic' )->withArgs( Prb::Str( 'sandbox realm' ), $auth_callback )->build()
    ->using( 'Sandbox_Showenv'  )->build()
    ->run( new Sandbox_Admin() )

  ->map( '/thrower' )
    ->run( new Sandbox_Thrower() )


->toMiddlewareApp();

$env = Prack_ModPHP_Compat::singleton()->extractEnv( $_SERVER );
$env->set( 'sandbox.root'     , Prb::Str( dirname( __FILE__ ) ) );
$env->set( 'sandbox.templates', Prb::Str( join( DIRECTORY_SEPARATOR, array( $env->get( 'sandbox.root' )->raw(), 'templates' ) ) ) );

list( $status, $headers, $body ) = $domain->call( $env )->toA()->raw();
$response = Prack_Response::with( $body, $status, $headers );

if ( $response->isOK() && !$env->get( 'SCRIPT_NAME' )->match( '/^public/' ) && $response->get( 'Content-Type' )->raw() == 'text/html' )
{
	$body = $response->getBody()->first();
	$body->gsubInPlace( '/<body>/', Prb::Str(
	  '<body><div id="runtime">Response time: '.$response->get( 'X-Runtime' )->raw().' seconds</div>'
	) );
}

Prack_ModPHP_Compat::singleton()->render( $response );

?>
