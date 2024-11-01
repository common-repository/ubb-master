<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if(class_exists('UBBFormat')) {
    return;
}

/**
 * UBB format object.
 * @author iambigasp
 *
 */
class UBBFormat {
    /**
     * UBB Id
     * @var int
     */
    var $id_;
    
    /**
     * UBB Name
     * @var string
     */
    var $name_;
    
    /**
     * UBB Format
     * @var string
     */
    var $format_;
    
    /**
     * Flags
     * @var int
     */
    var $flag_;
    
    /**
     * Flag to mark is this ubb enabled in post
     * @var int
     */
    const UBB_ENABLE_IN_POST        = 1;
    
    /**
     * Flag to mark is this ubb enabled in excerpt
     * @var unknown_type
     */
    const UBB_ENABLE_IN_EXCERPT     = 2;
    
    /**
     * Flag to mark is this ubb enabled in comment
     * @var unknown_type
     */
    const UBB_ENABLE_IN_COMMENT	    = 4;
    
    /**
     * Constructor
     * @param int $id
     * @param string $name
     * @param string $format
     * @param int $flag
     */
    private function UBBFormat($id, $name, $format, $flag) {
        $this->id_ = $id;
        $this->name_ = $name;
        $this->format_ = $format;
        $this->flag_ = $flag;
    }
    
    /**
     * Factory
     * @param int $id
     * @param string $name
     * @param string $format
     * @param int $flag
     * @return UBBFormat
     */
    static function create($id, $name, $format, $flag) {
        return new UBBFormat($id, $name, $format, $flag);
    }
    
    /**
     * Is this ubb enabled in post
     * @return boolean
     */
    function enable_in_post() {
        return $this->check_flag(self::UBB_ENABLE_IN_POST);
    }
    
    /**
     * Enable this ubb in post
     * @param boolean $enable
     * @return void
     */
    function set_enable_in_post($enable) {
        $enable ? $this->set_flag(self::UBB_ENABLE_IN_POST) : $this->unset_flag(self::UBB_ENABLE_IN_POST);
    }
    
    /**
     * Is this ubb enabled in excerpt
     * @return boolean
     */
    function enable_in_excerpt() {
        return $this->check_flag(self::UBB_ENABLE_IN_EXCERPT);
    }
    
    /**
     * Enable this ubb in excerpt
     * @param boolean $enable
     * @return void
     */
    function set_enable_in_excerpt($enable) {
        $enable ? $this->set_flag(self::UBB_ENABLE_IN_EXCERPT) : $this->unset_flag(self::UBB_ENABLE_IN_EXCERPT);
    }
    
    /**
     * Is this ubb enabled in comment
     * @return boolean
     */
    function enable_in_comment() {
        return $this->check_flag(self::UBB_ENABLE_IN_COMMENT);
    }
    
    /**
     * Enable this ubb in comment
     * @param boolean $enable
     */
    function set_enable_in_comment($enable) {
        $enable ? $this->set_flag(self::UBB_ENABLE_IN_COMMENT) : $this->unset_flag(self::UBB_ENABLE_IN_COMMENT);
    }
    
    /**
     * Check if the flag exists...
     * @param int $flag
     * @return boolean
     */
    private function check_flag($flag) {
        return ($this->flag_ & $flag) != 0;
    }

    /**
     * Set flag
     * @param int $flag
     */
    private function set_flag($flag) {
        $this->flag_ = $this->flag_ | $flag;
    }
    
    /**
     * Unset flag
     * @param int $flag
     */
    private function unset_flag($flag) {
        $this->flag_ = ($this->flag_ & ~$flag);
    }
}

?>