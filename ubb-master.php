<?php

/**
  Plugin Name: UBB Master
  Plugin URI: http://bigasp.com/archives/466
  Description: UBB Master is a simple plugin to replace your ubb code to custom format.
  Version: 0.1.0.0
  Author: bigasp
  Author URI: http://bigasp.com/
  
============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages(including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

For full license details see license.txt
============================================================================================================
**/

if ( class_exists( 'UBBMaster' ) )
	return;
	
require_once dirname( __FILE__ ).'/base/common.php';
require_once dirname( __FILE__ ).'/base/plugin-base.php';

/**
 * Main plugin class.
 * @author iambigasp
 *
 */
class UBBMaster extends PluginBase {
    /**
     * Constructor. Register plugin and filters. 
     */
    function UBBMaster() {
        $this->register_plugin( 'ubb-master', __FILE__ );
        if( is_admin() ) {
            $this->add_action( 'admin_menu' );
            $this->add_action( 'wp_print_scripts' );

			$this->register_activation( __FILE__ );
			$this->register_deactivation( __FILE__ );
        }
        
        $this->add_filter('the_content');
        $this->add_filter('the_excerpt');
        $this->add_filter('comments_array', 'comments_array', 10, 2);
    }
    
    /**
     * Get plugin version
     * @return string
     */
    function version() {
		$plugin_data = implode( '', file( __FILE__ ) );

		if ( preg_match( '|Version:(.*)|i', $plugin_data, $version ) )
			return trim( $version[1] );
		return '';
	}
    
    /**
     * WP Filter: the_content, translate post content.
     * @param string $content
     */
    function the_content($content) {
        require_once dirname( __FILE__ ).'/model/ubb-format.php';
        require_once dirname( __FILE__ ).'/model/ubb-translator.php';
        return UBBTranslator::get()->translate($content, UBBFormat::UBB_ENABLE_IN_POST);
    }
    
    /**
     * WP Filter: the_excerpt, translate post excerpt.
     * @param string $excerpt
     */
    function the_excerpt($excerpt) {
        require_once dirname( __FILE__ ).'/model/ubb-format.php';
        require_once dirname( __FILE__ ).'/model/ubb-translator.php';
        return UBBTranslator::get()->translate($excerpt, UBBFormat::UBB_ENABLE_IN_EXCERPT);
    }
    
    /**
     * WP Filter: comments_array, translate comments.
     * @param string $comments
     * @param int $post_id
     * @return array
     */
    function comments_array($comments, $post_id) {
        require_once dirname( __FILE__ ).'/model/ubb-format.php';
        require_once dirname( __FILE__ ).'/model/ubb-translator.php';
        
        $output_comments = $comments;
        for($i = 0, $comment_count = count($output_comments); $i < $comment_count; $i++) {
            $output_comments[$i]->comment_content = UBBTranslator::get()->translate($output_comments[$i]->comment_content, UBBFormat::UBB_ENABLE_IN_COMMENT);
        }
        
        return $output_comments;
    }
    
    /**
     * WP Filter: activate_ubb_master, install database.
     * @return void
     */
    function activate() {
		include_once dirname( __FILE__ ).'/model/db-installer.php';
		$installer = new DBInstaller();
		$installer->install();
	}

	/**
	 * WP Filter: deactivate_ubb_master, uninstall database.
     * @return void
	 */
	function deactivate() {
//		include_once dirname( __FILE__ ).'/model/db-installer.php';
//		$installer = new DBInstaller();
//		$installer->uninstall();
	}
	
    /**
	 * WP Action: admin_menu, add custom admin menu.
     * @return void
     */
    function admin_menu() {
        add_management_page( 'UBB Master', 'UBB Master', 'administrator', basename( __FILE__ ), array( $this, 'admin_entry' ) );
	}
	
    /**
     * WP Action: wp_print_scripts, inject custom script for admin menu.
     * @return void
     */
    function wp_print_scripts() {
		if ( isset($_GET['page']) && $_GET['page'] == basename( __FILE__ ) ) {
		    wp_enqueue_script( 'um-base64', $this->url().'/js/jquery.base64.js', array( ), $this->version() );
			wp_enqueue_script( 'um-admin', $this->url().'/js/um-admin.js', array( ), $this->version() );
		}
	}
	
    /**
     * WP Action: admin_entry, show admin page
     * @return void
     */
	function admin_entry() {
		if ( current_user_can( 'administrator' ) ) {
		    $plugin_op = $this->getArg('op');
		    
		    switch($plugin_op) {
	        case 'save':
	            $this->admin_save();
	            break;
	        case 'del':
	            $this->admin_del();
	            break;
	        default:
	            $this->admin_index();
		    }
		}
		else {
			$this->render_message( __( 'You are not allowed access to this resource', 'UBB Master' ) );
		}
	}
	
    /**
     * Get argument.
     * @param string $arg_name
     * @return string
     */
    function getArg($arg_name) {
        if(!isset($_GET[$arg_name]) && !isset($_POST[$arg_name])) {
	        return '';
        }
        
        return isset($_POST[$arg_name]) ? trim(stripslashes($_POST[$arg_name])) : trim(stripslashes($_GET[$arg_name]));
	}
	
	/**
     * Get argument in $_POST.
     * @param string $arg_name
     * @return string
     */
	function getPostArg($arg_name) {
	    return isset($_POST[$arg_name]) ? trim(stripslashes($_POST[$arg_name])) : '';
	}
	
	/**
     * Get argument in $_GET.
     * @param string $arg_name
     * @return string
     */
    function getGetArg($arg_name) {
	    return isset($_GET[$arg_name]) ? trim(stripslashes($_GET[$arg_name])) : '';
	}
	
	/**
	 * Admin index.
	 * @return void
	 */
	function admin_index() {
	    require_once dirname( __FILE__ ).'/model/ubb-format-manager.php';
	    $ubb_format_list = UBBFormatManager::get()->get_all_formats();
	    $this->render_admin( 'index', array( 'um_message' => '', 'ubb_format_list' => $ubb_format_list ) );
	}
	
	/**
	 * Save ubb.
	 * @return void
	 */
	function admin_save() {
	    require_once dirname( __FILE__ ).'/model/ubb-format.php';
	    require_once dirname( __FILE__ ).'/model/ubb-format-manager.php';
	    
	    $um_message = '';
	    $ubb_format = UBBFormat::create(intval($this->getPostArg('ubb-id')), $this->getPostArg('ubb-name'), $this->getPostArg('ubb-format'), 0);
        $ubb_format->set_enable_in_post(intval($this->getPostArg('ubb-enable-in-post')));
        $ubb_format->set_enable_in_excerpt(intval($this->getPostArg('ubb-enable-in-excerpt')));
        $ubb_format->set_enable_in_comment(intval($this->getPostArg('ubb-enable-in-comment')));
        if(false === UBBFormatManager::get()->save_format($ubb_format)) {
            $um_message = 'Save ubb format failed.';
        } else {
            $um_message = 'Save ubb format ok!';
        }
        
	    $ubb_format_list = UBBFormatManager::get()->get_all_formats();
	    $this->render_admin( 'index', array( 'um_message' => $um_message, 'ubb_format_list' => $ubb_format_list ) );
	}
	
	/**
	 * Delete ubb.
	 * @return void
	 */
	function admin_del() {
	    require_once dirname( __FILE__ ).'/model/ubb-format-manager.php';
	    
	    $um_message = '';
	    $ubb_id = intval($this->getGetArg('ubb-id'));
	    if(0 == $ubb_id) {
	        $um_message = 'Invalid argument, ubb-id = 0.';
	    } else {
	        if(UBBFormatManager::get()->delete_format($ubb_id) === false) {
	            $um_message = 'Delete ubb format failed.';
	        } else {
	            $um_message = 'Delete ubb format ok!';
	        }
	    }
	    
	    $ubb_format_list = UBBFormatManager::get()->get_all_formats();
	    $this->render_admin( 'index', array( 'um_message' => $um_message, 'ubb_format_list' => $ubb_format_list ) );
	}
};

global $ubb_master;
$ubb_master = new UBBMaster();

?>
