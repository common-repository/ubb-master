<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if(class_exists('UBBTranslator')) {
    return;
}

/**
 * Translate string with ubb code.
 * @author iambigasp
 *
 */
class UBBTranslator {
    /**
     * Singleton getter.
     * @return UBBTranslator
     */
    static function get() {
        if(!isset(UBBTranslator::$instance_)) {
            UBBTranslator::$instance_ = new UBBTranslator();
        }
        
        return UBBTranslator::$instance_;
    }
    
    /**
     * Constructor
     */
    private function UBBTranslator() {}
    
    /**
     * Translate string with ubb code.
     * @param string $text
     * @param int $context
     */
    function translate($text, $context) {
        $this->ubb_ = NULL;
        $this->context_ = $context;
        
        require_once dirname( __FILE__ ).'/ubb-format-manager.php';
        UBBFormatManager::get()->load_ubb_format();
                
        return preg_replace_callback("#\[([^\s\[\]]+)(\s[^\]]+)?\]([^\[]*)\[/([^\s\[\]]+)\]#U", array($this, 'on_ubb_match'), $text);
    }
    
    /**
     * Callback of preg_replace_callback, match whole ubb code.
     * @param array $ubb_match
     * @return string
     */
    function on_ubb_match($ubb_match) {
        require_once dirname( __FILE__ ).'/ubb.php';
        require_once dirname( __FILE__ ).'/ubb-format-manager.php';
        
        $this->ubb_ = UBB::parse($ubb_match);
        if(!$this->ubb_) {
            return $ubb_match[0];
        }
        
        $ubb_config = UBBFormatManager::get()->get_format_by_name($this->ubb_->name_, $this->context_);
        if(!$ubb_config || $ubb_config->format_ == '') {
            return $ubb_match[0];
        }
        
        return $this->translate_ubb($ubb_config);
    }
    
    /**
     * Translate ubb code.
     * @param array $ubb_config
     * @return string
     */
    function translate_ubb($ubb_config) {
        // UBB format: ${attr:attr_name}
        $ubb_format = $ubb_config->format_;
        $ubb_format = preg_replace_callback("/!\{(\w+):(\w+)\}/U", array($this, 'on_format_attribute_match'), $ubb_format);
        $ubb_format = str_replace('!{content}', $this->ubb_->content_, $ubb_format);
        $ubb_format = str_replace('!{encoded_content}', urlencode($this->ubb_->content_), $ubb_format);
        
        $this->ubb_ = NULL;
        
        return $ubb_format;
    }
    
    /**
     * Callback of preg_replace_callback, match attribute.
     * @param array $attr_match
     * @return string
     */
    function on_format_attribute_match($attr_match) {
        if(!$this->ubb_) {
            return '';
        }
        
        if(!isset($this->ubb_->attributes_[$attr_match[2]])) {
            return '';
        }
        
        if($attr_match == 'encoded_attr') {
            return urlencode($this->ubb_->attributes_[$attr_match[2]]);
        }
        
        return $this->ubb_->attributes_[$attr_match[2]];
    }
    
    /**
     * Singleton instance.
     * @var UBBTranslator
     */
    private static $instance_;
    
    /**
     * UBB Code, using in ubb matching.
     * Because preg_replace_callback can not accept argument.
     * @var UBB
     */
    private $ubb_;
    
    /**
     * Context, using in ubb matching.
     * Because preg_replace_callback can not accept argument.
     * @var int
     */
    private $context_;
};

?>