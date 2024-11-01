<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if(class_exists('DBInstaller')) {
    return;
}

/**
 * DB Installer
 * @author iambigasp
 *
 */
class DBInstaller {
	/**
	 * Installs DB tables
	 *
	 * @return void
	 **/
	function install() {
		global $wpdb;

		$sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}um_ubb_format_list (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `ubb_name` varchar(200) NOT NULL default '',
		  `ubb_format` TEXT NOT NULL,
		  `ubb_enable_flag` int(11) unsigned NOT NULL default 0,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM CHARSET=utf8";
		
		if ( version_compare( mysql_get_server_info(), '4.0.18', '<' ) ) {
			foreach ( $sql AS $pos => $line )
				$sql[$pos] = str_replace( 'ENGINE=MyISAM ', '', $line );
		}
		
		foreach ( $sql AS $pos => $line )
			$wpdb->query( $line );
			
		$ubb_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}um_ubb_format_list;"));
		if(0 == $ubb_count) {
			$rows_affected = $wpdb->insert( "{$wpdb->prefix}um_ubb_format_list", array( 'ubb_name' => 'search', 'ubb_format' => '<a target="blank" href="http://www.google.com/search?ie=UTF-8&q=!{encoded_content}">!{content}</a>', 'ubb_enable_flag' => 3 ) );
		}
	}
	
	/**
	 * Upgrade DB tables
	 * 
	 * @return void
	 */
	function upgrade( $old, $new ) {
		$this->install();
	}
	
	/**
	 * Removes DB tables
	 *
	 * @return void
	 **/
	function uninstall( $options = false ) {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}um_ubb_format_list" );
	}
}
?>