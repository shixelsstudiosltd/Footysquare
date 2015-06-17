<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Followed Terms List
 *
 * The html markup for the followed terms list
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Wpw_Fp_List_Terms extends WP_List_Table {
	
	var $model;
	
	function __construct(){
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
		
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'term',     //singular name of the listed records
							            'plural'    => 'terms',    //plural name of the listed records
							            'ajax'      => false       //does this table support ajax?
							        ) );   
		
	}
    
    /**
	 * Displaying Followed Categories
	 *
	 * Does prepare the data for displaying followed categories in the table.
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */	
	function display_follow_term() {
	
		$prefix = WPW_FP_META_PREFIX;
		
		//if search is call then pass searching value to function for displaying searching values
		$args = array();
		
		//in case of search make parameter for retriving search data
		if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$args['search']	= $_REQUEST['s'];
		}
		
		if( isset( $_GET['wpw_fp_post_type'] ) && !empty( $_GET['wpw_fp_post_type'] ) ) {
			$args['post_type']	= $_GET['wpw_fp_post_type'];
		}
		
		if( isset( $_GET['wpw_fp_taxonomy'] ) && !empty( $_GET['wpw_fp_taxonomy'] ) ) {
			$args['wpw_fp_taxonomy']	= $_GET['wpw_fp_taxonomy'];
		}
		
		//get followed post list data from database
		$data = $this->model->wpw_fp_get_follow_term_data( $args );
		
		foreach ($data as $key => $value){
			
			$posttype 	= get_post_meta( $value['ID'], $prefix.'post_type', true );
			$taxonomy 	= get_post_meta( $value['ID'], $prefix.'taxonomy_slug', true );
			
			$permalink = add_query_arg( array( 'page' => 'wpw-fp-term', 'termid' => $value['post_parent'], 'taxonomy' => $taxonomy ), admin_url( 'admin.php' ) );
			
			$userlist = '<a href="'.$permalink.'">'. __( 'View Followers', 'wpwfp' ) .'</a>';
			
			$data[$key]['users'] = $userlist;
			
			$termdata = get_term_by( 'id', $value['post_parent'], $taxonomy );
			$taxonomydata = get_taxonomy( $taxonomy );
			
			$data[$key]['termtitle'] = !empty( $termdata ) && isset( $termdata->name ) ? $termdata->name : '';
			$data[$key]['taxonomytitle'] = !empty( $taxonomydata ) && isset( $taxonomydata->label ) ? $taxonomydata->label : '';
			
			$data[$key]['posttype'] = $posttype;
			$data[$key]['taxonomy'] = $taxonomy;
			$data[$key]['termid'] = $value['post_parent'];
			
		}
		
		return $data;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
	function column_default( $item, $column_name ){
		switch( $column_name ) {
			case 'termtitle':
				$title = $item[ $column_name ];
		    	if( strlen( $title ) > 50 ) {
					$title = substr( $title, 0, 50 );
					$title = $title.'...';
				}
            default:
				return $item[ $column_name ];
        }
    }
    
	/**
	 * Mange post type column data
	 *
	 * Handles to modify post type column for listing table
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
    function column_posttype($item) {
    	
		// get all custom post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		
		return $post_types[$item[ 'posttype' ]]->label;
    }
    
	/**
	 * Mange taxonomytitle column data
	 *
	 * Handles to modify taxonomytitle column for listing table
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */
    function column_taxonomytitle($item) {
    	
		$taxonomy_sort_link = '';
		if( !empty( $item[ 'posttype' ] ) && !empty( $item[ 'taxonomy' ] ) && isset( $item[ 'taxonomytitle' ] ) ) {
			$taxonomy_sort_url = add_query_arg( array( 'page' => 'wpw-fp-term', 'wpw_fp_post_type' => $item[ 'posttype' ], 'wpw_fp_taxonomy' => $item[ 'taxonomy' ] ), admin_url( 'admin.php' ) );
			$taxonomy_sort_link = '<a href="' . $taxonomy_sort_url . '" >' . $item[ 'taxonomytitle' ] . '</a>';
		}
		return $taxonomy_sort_link;
    }
    
    /**
     * Manage Post Title Column
     *
     * @package Follow My Blog Post
     * @since 1.1.0
     */
    
    function column_termtitle($item){
    	
    	$pagestr = $pagenumber = '';
    	if( isset( $_GET['paged'] ) ) { $pagestr = '&paged=%s'; $pagenumber = $_GET['paged']; }
    	 
    	$actions['delete'] = sprintf('<a class="wpw-fp-post-title-delete wpw-fp-delete" href="?page=%s&action=%s&term[]=%s'.$pagestr.'">'.__('Delete', 'wpwfp').'</a>','wpw-fp-term','delete',$item['termid'], $pagenumber );
    	
         //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['termtitle'],
            /*$2%s*/ $this->row_actions( $actions )
        );
        
    }
   	
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['termid']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
     */
	function get_columns(){
	
        $columns = array(
    						'cb'      			=>	'<input type="checkbox" />', //Render a checkbox instead of text
				            'termtitle'			=>	__( 'Term Title', 'wpwfp' ),
				            'users'				=>	__(	'View Followers', 'wpwfp' ),
				            'taxonomytitle'		=>	__(	'Taxonomy', 'wpwfp' ),
				            'posttype'			=>	__(	'Post Type', 'wpwfp' ),
				        );
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
     */
	function get_sortable_columns() {
		
		$sortable_columns = array(
    								'termtitle'		=>	array( 'termtitle', true ),    //true means its already sorted
    								'taxonomytitle'	=>	array( 'taxonomy', true ),
    								'posttype'		=>	array( 'post_type', true )
						         );
						         
        return $sortable_columns;
    }
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No Followed Terms Found.', 'wpwfp' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
     */
	function get_bulk_actions() {
		//bulk action combo box parameter
		//if you want to add some more value to bulk action parameter then push key value set in below array
        $actions = array(
					            'delete'    => __('Delete','wpwfp')
					      );
        return $actions;
    }
    
	/**
     * Add filter for post types
     *
     * Handles to display records for particular post type
     * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
     */
    function extra_tablenav( $which ) {
    	
    	if( $which == 'top' ) {
    		
    		// get all custom post types
			$post_types = get_post_types( array( 'public' => true ), 'objects' );
			if( isset( $post_types['attachment'] ) ) { // Check attachment post type exists
				unset( $post_types['attachment'] );
			}
			
			$html = '';
			
    		$html .= '<div class="alignleft actions">';
    			
				if ( !empty( $post_types ) ) {
		
					$html .= '<select name="wpw_fp_taxonomy" id="wpw_fp_taxonomy" data-placeholder="' . __( 'Select a Taxonomy', 'wpwfp' ) . '">';
					
					$html .= '<option value="" ' .  selected( isset( $_GET['wpw_fp_taxonomy'] ) ? $_GET['wpw_fp_taxonomy'] : '', '', false ) . '>'.__( 'Select a Taxonomy', 'wpwfp' ).'</option>';
			
					foreach ( $post_types as $key => $post_type ) {
						
						$all_taxonomy = get_object_taxonomies( $key );
						if( !empty( $all_taxonomy ) ) { // Check taxonomy is not empty
							
							$html .= '<optgroup label="' . $post_type->labels->name . '">';
							foreach ( $all_taxonomy as $taxonomy_slug ) {
						
								if( $taxonomy_slug != 'post_format' ) {
									
									$tax = get_taxonomy( $taxonomy_slug );
									
									$args = array();
					
									if( !empty( $key ) ) {
										$args['post_type']	= $key;
										$args['wpw_fp_taxonomy']= $taxonomy_slug;
										$args['count']	= true;
									}
									
									//get followed post list count data from database
									$taxonomy_count = $this->model->wpw_fp_get_follow_term_data( $args );
									$taxonomy_count = !empty( $taxonomy_count ) ? $taxonomy_count : '0';
									$taxonomy_count = ' (' . $taxonomy_count . ')';
									
									$html .= '<option value="' . $taxonomy_slug . '" ' . selected( isset( $_GET['wpw_fp_taxonomy'] ) ? $_GET['wpw_fp_taxonomy'] : '', $taxonomy_slug, false ) . '>' . $tax->label . $taxonomy_count . '</option>';
								}
							}
							$html .= '</optgroup>';
						}
					}
				
					$html .= '</select>';
					
				}
				
    		$html .= '	<input type="submit" value="'.__( 'Filter', 'wpwfp' ).'" class="button" id="post-query-submit" name="">';
    		$html .= '</div>';
    		
			echo $html;
    	}
    }
    
    function prepare_items() {
        
		/**
         * First, lets decide how many records per page to show
         */
        $per_page = '10';
       
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
         /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        //$this->process_bulk_action();
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		$data = $this->display_follow_term();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'termid'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
       
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
									            'total_items' => $total_items,                  //WE have to calculate the total number of items
									            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
									            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
									        ) );
    }
    
}

//Create an instance of our package class...
$FollowedTermListTable = new Wpw_Fp_List_Terms();
	
//Fetch, prepare, sort, and filter our data...
$FollowedTermListTable->prepare_items();
		
?>

<div class="wrap">

    <!-- wpweb logo -->
	<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
    
	<h2 class="wpw-fp-list-title">
    	<?php _e( 'Followed Terms', 'wpwfp' ); ?>
    </h2>
    
    <?php 
    
    	//showing sorting links on the top of the list
    	$FollowedTermListTable->views(); 
    	
		if(isset($_GET['message']) && !empty($_GET['message']) ) { //check message
			
			if( $_GET['message'] == '3' ) { //check message
				
				echo '<div class="updated fade" id="message">
						<p><strong>'.__("Record (s) deleted successfully.",'wpwfp').'</strong></p>
					</div>'; 
				
			} 
		}
		
    ?>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get" class="wpw-fp-form">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        
        <!-- Search Title -->
        <?php $FollowedTermListTable->search_box( __( 'Search', 'wpwfp' ), 'wpwfp' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $FollowedTermListTable->display(); ?>
        
    </form>
</div><!--wrap-->