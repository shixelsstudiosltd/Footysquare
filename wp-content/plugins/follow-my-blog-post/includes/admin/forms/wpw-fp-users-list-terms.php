<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Followed Term Users List
 *
 * The html markup for the followed terms Users list
 * 
 * @package Follow My Blog Post
 * @since 1.1.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Wpw_Fp_Users_List_Terms extends WP_List_Table {
	
	var $model;
	
	function __construct(){
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
		
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'user',     //singular name of the listed records
							            'plural'    => 'users',    //plural name of the listed records
							            'ajax'      => false       //does this table support ajax?
							        ) );   
		
	}
    
    /**
	 * Displaying Followed Term Users
	 *
	 * Does prepare the data for displaying followed term users in the table.
	 * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
	 */	
	function display_follow_term_users() {
	
		$prefix = WPW_FP_META_PREFIX;
		
		//if search is call then pass searching value to function for displaying searching values
		$args = array();
		
		$args['termid'] = $_GET['termid'];
		
		//in case of search make parameter for retriving search data
		if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$args['search']	= $_REQUEST['s'];
		}
		
		if(isset($_REQUEST['wpw_fp_status']) && !empty($_REQUEST['wpw_fp_status'])) {
			$args['wpw_fp_status']	= $_REQUEST['wpw_fp_status'];
		}
		
		//get followed post list data from database
		$data = $this->model->wpw_fp_get_follow_term_users_data( $args );
		
		foreach ($data as $key => $value){
			
			// get user email from meta field
			$user_email = get_post_meta( $value['ID'], $prefix.'term_user_email', true );
			
			// get user is subscribed or not
			$subscribed = get_post_meta( $value['ID'], $prefix.'follow_status', true );
			
			// get view log link to view log for perticular user
			$permalink = add_query_arg( array( 'page' => 'wpw-fp-term', 'termid' => $_GET['termid'], 'taxonomy' => $_GET['taxonomy'], 'logid' => $value['ID'] ), admin_url( 'admin.php' ) );
			
			$logs = '<a href="'.$permalink.'">'. __( 'View Log', 'wpwfp' ) .'</a>';
			
			// set data
			$data[$key]['user_email']	=	!empty( $user_email ) ? '<a href="mailto:'.$user_email.'">'.$user_email.'</a>' : '';
			$data[$key]['subscribed']	=	$subscribed;
			$data[$key]['logs']			=	$logs;
			
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
			case 'post_author' 	:
				$user = $item[ $column_name ] != '0' ? __( 'Registered User', 'wpwfp' ) : __( 'Guest', 'wpwfp' );
				return $user;
			case 'subscribed' 	:
				if( isset( $_GET['paged'] ) ) {
					$status_url = add_query_arg( array( 'paged' => $_GET['paged'] ), admin_url( 'admin.php' ) );
				} else {
					$status_url = admin_url( 'admin.php' );
				}
				if( $item[ $column_name ] == '1' ) {
					$status_url = add_query_arg( array( 'page' => 'wpw-fp-term', 'termid' => $_GET['termid'], 'taxonomy' => $_GET['taxonomy'], 'wpw_fp_status' => 'subscribe' ), $status_url );
					$status_link = '<a href="' . $status_url . '" >' . __( 'Yes', 'wpwfp' ) . '</a>';
				} else {
					$status_url = add_query_arg( array( 'page' => 'wpw-fp-term', 'termid' => $_GET['termid'], 'taxonomy' => $_GET['taxonomy'], 'wpw_fp_status' => 'unsubscribe' ), $status_url );
					$status_link = '<a href="' . $status_url . '" >' . __( 'No', 'wpwfp' ) . '</a>';
				}
				return $status_link;
			default:
				return $item[ $column_name ];
		}
	}
	
    /**
     * Manage User Email Column
     *
     * @package Follow My Blog Post
     * @since 1.1.0
     */
    
    function column_user_email($item){
    	
    	$pagestr = $pagenumber = '';
    	if( isset( $_GET['paged'] ) ) { $pagestr = '&paged=%s'; $pagenumber = $_GET['paged']; }
    	
    	//Build row actions
    	if( $item['subscribed'] == '1' ) {
    		$actions['unsubscribe'] = sprintf('<a href="?page=%s&action=%s&user[]=%s&termid=%s&taxonomy=%s'.$pagestr.'">'.__('Unsubscribe', 'wpwfp').'</a>','wpw-fp-term','unsubscribe',$item['ID'],$_GET['termid'],$_GET['taxonomy'], $pagenumber);
    	} else {
    		$actions['subscribe'] = sprintf('<a href="?page=%s&action=%s&user[]=%s&termid=%s&taxonomy=%s'.$pagestr.'">'.__('Subscribe', 'wpwfp').'</a>','wpw-fp-term','subscribe',$item['ID'],$_GET['termid'],$_GET['taxonomy'], $pagenumber);
    	}
    	
    	$actions['delete'] = sprintf('<a class="wpw-fp-users-delete wpw-fp-delete" href="?page=%s&action=%s&user=%s&termid=%s&taxonomy=%s'.$pagestr.'">'.__('Delete', 'wpwfp').'</a>','wpw-fp-term','delete',$item['ID'],$_GET['termid'],$_GET['taxonomy'], $pagenumber );
    	
         //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['user_email'],
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
	 * @since 1.1.0
     */
	function get_columns(){
	
		global $wpw_fp_options;
		
        $columns = array(
    						'cb'      			=>	'<input type="checkbox" />', //Render a checkbox instead of text
				            'user_email'		=>	__( 'User Email', 'wpwfp' ),
				            'post_author'		=>	__(	'User Type', 'wpwfp' ),
				            'subscribed'		=>	__(	'Subscribed', 'wpwfp' ),
				        );
        if( isset( $wpw_fp_options['enable_log'] ) && $wpw_fp_options['enable_log'] == '1' ) {
        	$columns['logs'] = __(	'View Logs', 'wpwfp' );
        }
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
    								'user_email'	=>	array( 'user_email', true ),   //true means its already sorted
    								'post_author'	=>	array( 'post_author', true ),
    								'subscribed'	=>	array( 'subscribed', true )
						         );
						         
        return $sortable_columns;
    }
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No Users Found.', 'wpwfp' );
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
        						'subscribe'		=> __('Subscribe','wpwfp'),
        						'unsubscribe'	=> __('Unsubscribe','wpwfp'),
					            'delete'    	=> __('Delete','wpwfp')
					      );
        return $actions;
    }
    
	/**
     * Add filter for subscribe/unscribe
     *
     * Handles to display records for particular subscribe/unscribe
     * 
	 * @package Follow My Blog Post
	 * @since 1.1.0
     */
    function extra_tablenav( $which ) {
    	
    	if( $which == 'top' ) {
    		
			$html = '';
			
			$all_status = array(
										'subscribe'		=> __( 'Subscribed', 'wpwfp' ),
										'unsubscribe'	=> __( 'Unsubscribed', 'wpwfp' ),
									);
			
    		$html .= '<div class="alignleft actions">';
    		
				$html .= '<select name="wpw_fp_status" id="wpw_fp_status" data-placeholder="' . __( 'All', 'wpwfp' ) . '">';
				
				$html .= '<option value="" ' .  selected( isset( $_GET['wpw_fp_status'] ) ? $_GET['wpw_fp_status'] : '', '', false ) . '>'.__( 'All', 'wpwfp' ).'</option>';
		
				if(isset($_REQUEST['wpw_fp_status']) && !empty($_REQUEST['wpw_fp_status'])) {
					$args['wpw_fp_status']	= $_REQUEST['wpw_fp_status'];
				}
				
				foreach ( $all_status as $key => $status ) {
					
					$args = array();
	
					if( !empty( $key ) ) {
						$args['wpw_fp_status']	= $key;
						$args['count']	= true;
					}
					
					$args['termid'] = $_GET['termid'];
					
					//in case of search make parameter for retriving search data
					if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
						$args['search']	= $_REQUEST['s'];
					}
					
					//get followed post list count data from database
					$status_count = $this->model->wpw_fp_get_follow_term_users_data( $args );
					$status_count = !empty( $status_count ) ? $status_count : '0';
					$status_count = ' (' . $status_count . ')';
					$html .= '<option value="' . $key . '" ' . selected( isset( $_GET['wpw_fp_status'] ) ? $_GET['wpw_fp_status'] : '', $key, false ) . '>' . $status . $status_count . '</option>';
				}
			
				$html .= '</select>';
				
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
		$data = $this->display_follow_term_users();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'post_name'; //If no sort, default to title
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
$FollowedUsersTermListTable = new Wpw_Fp_Users_List_Terms();
	
//Fetch, prepare, sort, and filter our data...
$FollowedUsersTermListTable->prepare_items();
		
?>

<div class="wrap">
    <?php 
    	$termid 	= isset( $_GET['termid'] ) ? $_GET['termid'] : '';
    	$taxonomy 	= isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';
    	$term_data 	= get_term_by( 'id', $termid, $taxonomy );
    	$title 		= isset( $term_data->name ) ? $term_data->name : '';
    	if( strlen( $title ) > 50 ) {
			$title = substr( $title, 0, 50 );
			$title = $title.'...';
		}
		//back url to go back on the page
		$backurl = add_query_arg( array( 'page' => 'wpw-fp-term' ), admin_url( 'admin.php' ) );
    ?>
    
    <!-- wpweb logo -->
	<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
    
	<h2 class="wpw-fp-list-title">
    	<?php printf( __( 'Followers For %s', 'wpwfp' ), $title ); ?>
    	<a href="<?php echo $backurl;?>" class="button"><?php _e( 'Go Back', 'wpwfp' );?></a>
    </h2>
    
    <?php 
    
    	//showing sorting links on the top of the list
    	$FollowedUsersTermListTable->views(); 
    	
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
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        <input type="hidden" name="termid" value="<?php echo  $_GET['termid']; ?>" />
        <input type="hidden" name="taxonomy" value="<?php echo  $_GET['taxonomy']; ?>" />
        
        <!-- Search Title -->
        <?php $FollowedUsersTermListTable->search_box( __( 'Search', 'wpwfp' ), 'wpwfp' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $FollowedUsersTermListTable->display(); ?>
        
    </form>
</div><!--wrap-->