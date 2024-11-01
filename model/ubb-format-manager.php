<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if(class_exists('UBBFormatManager')) {
    return;
}

/**
 * Singleton, provide function for saving and loading ubb format.
 * @author iambigasp
 *
 */
class UBBFormatManager {
    /**
     * Singleton getter
     * 
     * @return UBBFormatManager
     */
    static function get() {
        if(!isset(UBBFormatManager::$instance_)) {
            UBBFormatManager::$instance_ = new UBBFormatManager();
        }
        
        return UBBFormatManager::$instance_;
    }
    
    /**
     * Constructor
     */
    private function UBBFormatManager() {
    }
    
    /**
     * Save UBB format to database.
     * @param UBBFormat $ubb_format
     * @return void
     */
    function save_format($ubb_format) {
        global $wpdb;
        
        if('' == $ubb_format->name_ || '' == $ubb_format->format_) {
            return false;
        }
        
        if(0 == $ubb_format->id_) {
            // Add format
            return $wpdb->insert( "{$wpdb->prefix}um_ubb_format_list",
                            array( 'ubb_name' => $ubb_format->name_, 'ubb_format' => $ubb_format->format_, 'ubb_enable_flag' => $ubb_format->flag_ ),
                            array( '%s', '%s', '%d' ) );
        } else {
            // Update format
            $wpdb->update( "{$wpdb->prefix}um_ubb_format_list",
                            array( 'ubb_name' => $ubb_format->name_, 'ubb_format' => $ubb_format->format_, 'ubb_enable_flag' => $ubb_format->flag_ ),
                            array( 'id' => $ubb_format->id_ ),
                            array( '%s', '%s', '%d' ),
                            array( '%d' ) );
                            
            return true;
        }
    }
    
    /**
     * Delete a ubb format by id
     * @param int $ubb_id
     */
    function delete_format($ubb_id) {
        global $wpdb;
        
        $ubb_id_for_query = intval($ubb_id);
        return $wpdb->query("DELETE FROM {$wpdb->prefix}um_ubb_format_list WHERE `id` = '{$ubb_id_for_query}';");
    }
    
    /**
     * Get a ubb format by id
     * @param int $ubb_id
     * @return UBBFormat
     */
    function get_format_by_id($ubb_id) {
        if(!isset($this->ubb_format_list_)) {
            $this->load_ubb_format();
        }
        
        return $this->ubb_format_list_[$context][$ubb_id];
    }
    
    /**
     * Get a ubb format by name and context
     * @param string $ubb_name
     * @param int $context
     * @return UBBFormat
     */
    function get_format_by_name($ubb_name, $context) {
        if(!isset($this->ubb_format_list_)) {
            $this->load_ubb_format();
        }
        
        if(!isset($this->ubb_format_map_[$context][$ubb_name])) {
            return NULL;
        }
        
        return $this->ubb_format_list_[$this->ubb_format_map_[$context][$ubb_name]];
    }
    
    /**
     * Get all ubb format
     * @return array <UBBFormat>
     */
    function get_all_formats() {
        if(!isset($this->ubb_format_list_)) {
            $this->load_ubb_format();
        }
        
        return $this->ubb_format_list_;
    }
    
    /**
     * Load all ubb format in database
     * @return void
     */
    function load_ubb_format() {
        if(isset($this->ubb_format_list_)) {
            return;
        }
        
        global $wpdb;
        
        require_once dirname( __FILE__ ).'/ubb-format.php';
        
        // Load ubb settings here.
        $this->ubb_format_list_ = array();
        $this->ubb_format_map_ = array();
        
        $raw_format_list = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}um_ubb_format_list;");
        foreach($raw_format_list as $format) {
            $ubb = UBBFormat::create($format->id, $format->ubb_name, $format->ubb_format, $format->ubb_enable_flag);
            if($ubb->enable_in_post()) {
                $this->ubb_format_map_[UBBFormat::UBB_ENABLE_IN_POST][$format->ubb_name] = $ubb->id_;
            }
            
            if($ubb->enable_in_excerpt()) {
                $this->ubb_format_map_[UBBFormat::UBB_ENABLE_IN_EXCERPT][$format->ubb_name] = $ubb->id_;
            }
            
            if($ubb->enable_in_comment()) {
                $this->ubb_format_map_[UBBFormat::UBB_ENABLE_IN_COMMENT][$format->ubb_name] = $ubb->id_;
            }
            
            $this->ubb_format_list_[$ubb->id_] = $ubb;
        }
    }
    
    /**
     * Instance of UBBFormatManager singleton
     * @var UBBFormatManager
     */
    private static $instance_;
    
    /**
     * Array of ubb format instances
     * @var array
     */
    private $ubb_format_list_;
    
    /**
     * Map: [Context][UBB Name] => UBB Id
     * @var array
     */
    private $ubb_format_map_;
}

?>