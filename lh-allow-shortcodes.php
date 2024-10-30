<?php
/**
 * Plugin Name: LH Allow Shortcodes
 * Plugin URI: https://lhero.org/portfolio/lh-allow-shortcodes/
 * Description: Allow shortcodes in blocks href attribute
 * Author: Peter Shaw
 * Version: 1.00
 * Author URI: https://shawfactor.com/
 * Text Domain: lh_allow_shortcodes
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('LH_Allow_shortcodes_plugin')) {


class LH_Allow_shortcodes_plugin {
    
    private static $instance;

static function return_plugin_namespace(){

return 'lh_allow_shortcodes';

}

public function allow_shortcodes_in_attributes($content){
    
    	// No need to do anything if there isn't any content or if DomDocument isn't supported
	if ( empty( $content ) || ! class_exists( 'DOMDocument' ) ) {
		return $content;
	}

libxml_use_internal_errors(true); 

	// Create new dom object
	$dom = new DOMDocument;

	// Load html into the object
	$dom->loadHTML( mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	
	
		// Loop through all content links
	foreach( $dom->getElementsByTagName( 'a' ) as $link ) {
	    
if($link->hasAttribute('href')) {
	    
	    
$href = $link->getAttribute( 'href' );	

$link->setAttribute('href',do_shortcode($href));

}
	    
	    
	}
	
$content = $dom->saveHTML();
libxml_clear_errors();
    
    
    
return $content;
    
}

public function add_wp_body_open_hooks(){
    
add_filter( 'the_content', array($this,'allow_shortcodes_in_attributes'), 1 );

}

public function plugin_init(){

//add others on body open so that it only runs when needed
add_action('wp_body_open', array($this,'add_wp_body_open_hooks'));





}

  /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }



public function __construct() {

//run whatever on plugins loaded
add_action( 'plugins_loaded', array($this,'plugin_init'));

}


}



$lh_allow_shortcodes_instance = LH_Allow_shortcodes_plugin::get_instance();



}

?>