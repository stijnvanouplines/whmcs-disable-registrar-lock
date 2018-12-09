<?php

use WHMCS\View\Menu\Item as MenuItem;
use WHMCS\Database\Capsule;

add_hook( 'ClientAreaPageDomainDetails', 1, function( array $vars ) {
    $getEnabled = Capsule::table( 'mod_disable_registrar_lock' )->first();
    $enabled = json_decode( $getEnabled->enabled );

    $current = Menu::context( 'domain' );
    $domain = $current->domain;
    $tld = substr( $domain, strrpos( $domain, "." ) + 1 );

    if ( ! in_array( $tld, $enabled ) ) {
        $vars['managementoptions']['locking'] = false;
        $vars['lockstatus'] = false;

        return $vars;
    }

});

add_hook( 'ClientAreaPrimarySidebar', 1, function( MenuItem $primarySidebar ) {
    $getEnabled = Capsule::table( 'mod_disable_registrar_lock' )->first();
    $enabled = json_decode( $getEnabled->enabled );

    $current = Menu::context( 'domain' );
    $domain = $current->domain;
    $tld = substr( $domain, strrpos( $domain, "." ) + 1 );

    if ( ! in_array( $tld, $enabled ) ) {
        if ( ! is_null( $primarySidebar->getChild( 'Domain Details Management' ) ) ) {
            $primarySidebar->getChild( 'Domain Details Management' )->removeChild( 'Registrar Lock Status' );
        }
    }
});
