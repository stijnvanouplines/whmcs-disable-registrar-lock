<?php

if ( ! defined( 'WHMCS' ) ) {
    die( 'This file cannot be accessed directly' );
}

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\DisableRegistrarLock\Admin\AdminDispatcher;

/**
 * Define addon module configuration parameters.
 *
 * @return array
 */
function disable_registrar_lock_config()
{
    return array(
        'name'          => 'Disable Registrar Lock',
        'description'   => 'With this module you can easily disable registrar lock option for certain TLDs.',
        'author'        => 'Solitweb',
        'language'      => 'english',
        'version'       => '1.0',
        'fields'        => []
    );
}

/**
 * Activate.
 *
 * @return array Optional success/failure message
 */
function disable_registrar_lock_activate()
{
	$LANG = $vars['_lang'];

	try {
		if ( ! Capsule::schema()->hasTable( 'mod_disable_registrar_lock' ) ) {
			Capsule::schema()->create( 'mod_disable_registrar_lock', function ( $table ) {
				$table->increments( 'id' );
				$table->json( 'enabled' )->nullable();
            });
            
            Capsule::table( 'mod_disable_registrar_lock' )->insert([
                'enabled' => json_encode( array() ),
            ]);
		}
	} catch ( Exception $e ) {
		return [
			'status'        => 'error',
			'description'   => 'Cannot create table! (' . $e->getMessage() , ')'
		];
    }

	return [
		'status'        => 'success',
		'description'   => 'The module is activated successfully.'
	];
}

/**
 * Deactivate.
 *
 * @return array Optional success/failure message
 */
function disable_registrar_lock_deactivate()
{
	try {
        Capsule::schema()->dropIfExists( 'mod_disable_registrar_lock' );

		return [
			'status'        => 'success',
			'description'   => 'Module deactivated successfully!'
		];
	}
	catch ( Exception $e ) {
		return [
			'status'        => 'error',
			'description'   => 'Unable to drop table! (' . $e->getMessage() .')'
		];
	}
}

/**
 * Admin Area Output.
 *
 * @return string
 */
function disable_registrar_lock_output( $vars )
{
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

    $dispatcher = new AdminDispatcher();

    $response = $dispatcher->dispatch( $action, $vars );

    echo $response;
}
