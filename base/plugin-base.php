<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if( class_exists('PluginBase') ) {
    return;
}

require_once dirname( __FILE__ ).'/search-plugin.php';


class PluginBase extends Search_Plugin {
    function register_activation_ex( $pluginfile, $function = '' ) {
        return $function == '' ? 'on_activate' : $function;
	}

	function register_deactivation_ex( $pluginfile, $function = '' ) {
		add_action( 'deactivate_'.basename( dirname( $pluginfile ) ).'/'.basename( $pluginfile ), array( &$this, $function == '' ? 'on_deactivate' : $function ) );
	}
};

?>
