<?php

if( !defined('PLUGIN_MAGIC') ) {
    die('Access denied!');
}

if(class_exists('UBB')) {
    return;
}

/**
 * Matched ubb code.
 * @author iambigasp
 *
 */
class UBB {
    /**
     * UBB Name
     * @var string
     */
    var $name_;
    
    /**
     * UBB attributes
     * @var array
     */
    var $attributes_;
    
    /**
     * UBB Content
     * @var string
     */
    var $content_;
    
    /**
     * Constructor
     * @param string $name
     * @param string $content
     * @param array $attributes
     */
    function UBB($name, $content, $attributes) {
        $this->name_ = $name;
        $this->attributes_ = array();
        $this->content_ = $content;
        
        if(is_array($attributes)) {
            $this->attributes_ = $attributes;
        }
    }
    
    /**
     * UBB parser, should pass preg_match_callback result as argument.
     * @param array $ubb_match
     * @return NULL|UBB
     */
    static function parse(&$ubb_match) {
        if($ubb_match[1] != $ubb_match[4]) return NULL;
        UBB::parse_attributes($attributes, $ubb_match[2]);
        return new UBB($ubb_match[1], $ubb_match[3], $attributes);
    }
    
    /**
     * UBB attribute parser.
     * Find attribute and pass them to another parse_attributes().
     * @param array $attributes
     * @param string $text
     */
    private static function parse_attributes(&$attributes, $text) {
        $attr_start = 0;
        $end = 0;
        
        for($i = 0, $length = strlen($text); $i < $length;) {
            $has_quota = false;
            $in_quota = false;
            $is_valid_quota = true;
            
            while($i < $length && ($text[$i] == ' ' || $text[$i] == "\t")) {
                ++$i;
            }
            
            if($i == $length) break;
            
            $attr_start = $i;
            
            while($i < $length) {
                if($text[$i] == '"') {
                    if($i > 0 && $text[$i - 1] == '\\') {
                        $is_valid_quota = false;
                    } else {
                        $is_valid_quota = true;
                    }
                    
                    if($in_quota && $is_valid_quota) {
                        break;
                    } else if($is_valid_quota) {
                        $has_quota = true;
                        $in_quota = true;
                    }
                } else if($text[$i] == ' ' || $text[$i] == "\t") {
                    if(!$in_quota) {
                        break;
                    }
                }
                
                ++$i;
            }
            
            if($i == $length) --$i;
            
            $attr_end = ++$i;
            
            $attribute = substr($text, $attr_start, $attr_end - $attr_start);
            UBB::parse_attribute($attributes, $attribute, $has_quota);
        }
    }
    
    /**
     * Parse attribute.
     * @param array $attributes
     * @param string $text
     * @param boolean $has_quota
     */
    private static function parse_attribute(&$attributes, $text, $has_quota) {
        if(strpos($text, '=') == -1) {
            return;
        }
        
        list($key, $value) = explode('=', $text);
        
        if($key == '') {
            return;
        }
        
        if(!$has_quota) {
            $attributes[$key] = $value;
            return;
        }
        
        if($value[0] != '"') {
            return;
        }
        
        $value_end = strlen($value);
        if($value[$value_end - 1] == '"') --$value_end;
        $attributes[$key] = substr($value, 1, $value_end - 1);
        return;
    }
};

?>