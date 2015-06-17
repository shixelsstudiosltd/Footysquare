<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Followed Posts List
 *
 * The html markup for the followed posts list
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Wpw_Fp_List extends WP_List_Table {
	
	var $model;
	
	function __construct(){
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
		
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'post',     //singular name of the listed records
							            'plural'    => 'posts',    //plural name of the listed records
							            'ajax'      => false       //does this table support ajax?
							        ) );   
		
	}
    
    /**
	 * Displaying Followed Posts
	 *
	 * Does prepare the data for displaying followed posts in the table.
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */	
	function display_follow_post() {
	
		//if search is call then pass searching value to function for displaying searching values
		$args = array();
		
		//in case of search make parameter for retriving search data
		if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$args['search']	= $_REQUEST['s'];
		}
		
		if( isset( $_GET['wpw_fp_post_type'] ) && !empty( $_GET['wpw_fp_post_type'] ) ) {
			$args['post_type']	= $_GET['wpw_fp_post_type'];
		}
		
		//get followed post list data from database
		$data = $this->model->wpw_fp_get_follow_post_data( $args );
		
		foreach ($data as $key => $value){
			
			$permalink = add_query_arg( array( 'page' => 'wpw-fp-post', 'postid' => $value['ID'] ), admin_url( 'admin.php' ) );
			
			$userlist = '<a href="'.$permalink.'">'. __( 'View Followers', 'wpwfp' ) .'</a>';
			
			$data[$key]['users'] = $userlist;
			$data[$key]['post_type'] = isset( $value['post_type'] ) ? $value['post_type'] : '';
			
		}
		
		return $data;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */
	function column_default( $item, $column_name ){
		switch( $column_name ) {
			case 'post_title':
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
	 * @since 1.0.0
	 */
    function column_post_type($item) {
    	
		// get all custom post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		
		$post_type_sort_link = '';
		if( !empty( $item[ 'post_type' ] ) && isset( $post_types[$item[ 'post_type' ]]->label ) ) {
			$post_type_sort_url = add_query_arg( array( 'page' => 'wpw-fp-post', 'wpw_fp_post_type' => $item[ 'post_type' ] ), admin_url( 'admin.php' ) );
			$post_type_sort_link = '<a href="' . $post_type_sort_url . '" >' . $post_types[$item[ 'post_type' ]]->label . '</a>';
		}
		return $post_type_sort_link;
    }
    
    /**
     * Manage Post Title Column
     *
     * @package Follow My Blog Post
     * @since 1.0.0
     */
    
    function column_post_title($item){
    	
    	$pagestr = $pagenumber = '';
    	if( isset( $_GET['paged'] ) ) { $pagestr = '&paged=%s'; $pagenumber = $_GET['paged']; }
    	 
    	$actions['delete'] = sprintf('<a class="wpw-fp-post-title-delete wpw-fp-delete" href="?page=%s&action=%s&post[]=%s'.$pagestr.'">'.__('Delete', 'wpwfp').'</a>','wpw-fp-post','delete',$item['ID'], $pagenumber );
    	
         //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['post_title'],
            /*$2%s*/ $this->row_actions( $actions )
        );
        
    }
   	
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
     */
	function get_columns(){
	
        $columns = array(
    						'cb'      			=>	'<input type="checkbox" />', //Render a checkbox instead of text
				            'post_title'		=>	__( 'Post Name', 'wpwfp' ),
				            'users'				=>	__(	'View Followers', 'wpwfp' ),
				            'post_type'			=>	__(	'Post Type', 'wpwfp' ),
				        );
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
     */
	function get_sortable_columns() {
		
		$sortable_columns = array(
    								'post_title'	=>	array( 'post_title', true ),    //true means its already sorted
    								'post_type'		=>	array( 'post_type', true )
						         );
						         
        return $sortable_columns;
    }
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No Followed Posts Found.', 'wpwfp' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
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
	 * @since 1.0.0
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
		
					$html .= '<select name="wpw_fp_post_type" id="wpw_fp_post_type" data-placeholder="' . __( 'Select a Post Type', 'wpwfp' ) . '">';
					
					$html .= '<option value="" ' .  selected( isset( $_GET['wpw_fp_post_type'] ) ? $_GET['wpw_fp_post_type'] : '', '', false ) . '>'.__( 'Select a Post Type', 'wpwfp' ).'</option>';
			
					foreach ( $post_types as $key => $post_type ) {
						
						$args = array();
		
						if( !empty( $key ) ) {
							$args['post_type']	= $key;
							$args['count']	= true;
						}
						
						//get followed post list count data from database
						$post_count = $this->model->wpw_fp_get_follow_post_data( $args );
						$post_count = !empty( $post_count ) ? $post_count : '0';
						$post_count = ' (' . $post_count . ')';
						$html .= '<option value="' . $key . '" ' . selected( isset( $_GET['wpw_fp_post_type'] ) ? $_GET['wpw_fp_post_type'] : '', $key, false ) . '>' . $post_type->label . $post_count . '</option>';
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
		$data = $this->display_follow_post();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'post_title'; //If no sort, default to title
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
$FollowedPostsListTable = new Wpw_Fp_List();
	
//Fetch, prepare, sort, and filter our data...
$FollowedPostsListTable->prepare_items();
		
?>

<div class="wrap">

    <!-- wpweb logo -->
	<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
    
	<h2 class="wpw-fp-list-title">
    	<?php _e( 'Followed Posts', 'wpwfp' ); ?>
    </h2>
    
    <?php 
    
    	//showing sorting links on the top of the list
    	$FollowedPostsListTable->views(); 
    	
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
        <?php $FollowedPostsListTable->search_box( __( 'Search', 'wpwfp' ), 'wpwfp' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $FollowedPostsListTable->display(); ?>
        
    </form>
</div><!--wrap-->