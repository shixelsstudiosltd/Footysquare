<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Meta Box Validate Class
 *
 * Handles all the functions to validate meta box data.
 *
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if( ! class_exists( 'Wpw_Fp_Meta_Box_Validate' ) ) :

class Wpw_Fp_Meta_Box_Validate {

	var $model;
	
	public function Wpw_Fp_Meta_Box_Validate() {
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
	}
	
	public function date_str_to_time( $data ) {
            return strtotime( $data );
    }
    
    public function escape_html( $data ) {
	
    	return $this->model->wpw_fp_escape_slashes_deep( $data ); 
    }
	
} // End Class

endif; // End Check Class Exists