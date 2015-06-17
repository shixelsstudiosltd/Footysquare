<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Followed Post User Logs List
 *
 * The html markup for the followed posts user logs list
 * 
 * @package Follow My Blog Post
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Wpw_Fp_User_Logs_List extends WP_List_Table {
	
	var $model;
	
	function __construct(){
		
		global $wpw_fp_model;
		
		$this->model = $wpw_fp_model;
		
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'userlog',     //singular name of the listed records
							            'plural'    => 'userlogs',    //plural name of the listed records
							            'ajax'      => false       //does this table support ajax?
							        ) );   
		
	}
    
    /**
	 * Displaying Followed Post User Logs
	 *
	 * Does prepare the data for displaying followed post users in the table.
	 * 
	 * @package Follow My Blog Post
	 * @since 1.0.0
	 */	
	function display_follow_post_user_logs() {
	
		$prefix = WPW_FP_META_PREFIX;
		
		//if search is call then pass searching value to function for displaying searching values
		$args = array();
		
		$args['logid'] = $_GET['logid'];
		
		//in case of search make parameter for retriving search data
		if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$args['search']	= $_REQUEST['s'];
		}
		
		//get followed post list data from database
		$data = $this->model->wpw_fp_get_follow_post_user_logs_data( $args );
		
		foreach ($data as $key => $value){
			
			// get email data from meta field
			$email_data = get_post_meta( $value['ID'], $prefix.'log_email_data', true );
			$mail_part = explode( '%$%$%', $email_data );
			
			// set data
			$data[$key]['mail_subject'] = isset( $mail_part[0] ) ? $mail_part[0] : '';
			$data[$key]['mail_body'] 	= isset( $mail_part[1] ) ? $mail_part[1] : '';
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
			case 'mail_subject' :
				return $item[ $column_name ];
			case 'mail_body' :
				return $item[ $column_name ];
			case 'post_date' :
				$date = date( get_option('date_format'), strtotime( $item[ $column_name ] ) );
				return $date;
			default:
				return $item[ $column_name ];
		}
	}
   	
	/**
     * Manage User Email Column
     *
     * @package Follow My Blog Post
     * @since 1.0.0
     */
    function column_mail_subject($item){
    	
    	$pagestr = $pagenumber = '';
    	if( isset( $_GET['paged'] ) ) { $pagestr = '&paged=%s'; $pagenumber = $_GET['paged']; }
    	
    	//Build row action
    	$actions['delete'] = sprintf('<a class="wpw-fp-logs-delete wpw-fp-delete" href="?page=%s&action=%s&userlog=%s&postid=%s&logid=%s'.$pagestr.'">'.__('Delete', 'wpwfp').'</a>','wpw-fp-post','delete',$item['ID'],$_GET['postid'],$_GET['logid'], $pagenumber );
    	
         //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['mail_subject'],
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
				            'mail_subject'		=>	__( 'Email Subject', 'wpwfp' ),
				            'mail_body'			=>	__(	'Email Body', 'wpwfp' ),
				            'post_date'			=>	__(	'Post Date', 'wpwfp' )
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
    								'post_date'		=>	array( 'post_date', true )
						         );
						         
        return $sortable_columns;
    }
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No User Logs Found.', 'wpwfp' );
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
        						'delete'    	=> __('Delete','wpwfp')
					      );
        return $actions;
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
		$data = $this->display_follow_post_user_logs();
		
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'post_date'; //If no sort, default to title
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
$FollowedUserLogsListTable = new Wpw_Fp_User_Logs_List();
	
//Fetch, prepare, sort, and filter our data...
$FollowedUserLogsListTable->prepare_items();
		
$prefix = WPW_FP_META_PREFIX;

?>

<div class="wrap">
    <?php 
    	$data = get_post( $_GET['postid'] );
    	$email = get_post_meta( $_GET['logid'], $prefix.'post_user_email', true );
    	$title = $data->post_title;
    	if( strlen( $title ) > 50 ) {
			$title = substr( $title, 0, 50 );
			$title = $title.'...';
		}
		//back url to go back on the page
		$backurl = add_query_arg( array( 'page' => 'wpw-fp-post', 'postid' => $_GET['postid'] ), admin_url( 'admin.php' ) );
    ?>
    
    <!-- wpweb logo -->
	<img src="<?php echo WPW_FP_IMG_URL . '/wpweb-logo.png'; ?>" class="wpweb-logo" alt="<?php _e( 'WP Web Logo', 'wpwfp' );?>" />
    
    <h2 class="wpw-fp-list-title">
    	<?php _e( 'Followers Email logs', 'wpwfp' ); ?>
    	<a href="<?php echo $backurl;?>" class="button"><?php _e( 'Go Back', 'wpwfp' );?></a>
    </h2>
    
    <?php 
    	//showing sorting links on the top of the list
    	$FollowedUserLogsListTable->views();
    	
		if(isset($_GET['message']) && !empty($_GET['message']) ) { //check message
			
			if( $_GET['message'] == '3' ) { //check message
				
				echo '<div class="updated fade" id="message">
						<p><strong>'.__("Record (s) deleted successfully.",'wpwfp').'</strong></p>
					</div>'; 
				
			} 
		}
		
    ?>
    
    <div class="wpw_fp_top_down"></div>
    
	<div class="wpw_fp_view"><strong><?php _e( 'User E-Mail :', 'wpwfp' ); ?></strong></div>
	<span><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span>
	<div class="wpw_fp_clear_both"></div>
	<div class="wpw_fp_view"><strong><?php _e( 'Post Title :', 'wpwfp' ); ?></strong></div>
	<span><?php echo $title; ?></span>
	
	<div class="wpw_fp_top_down"></div>
	
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get" class="wpw-fp-form">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        <input type="hidden" name="postid" value="<?php echo $_GET['postid']; ?>" />
        <input type="hidden" name="logid" value="<?php echo $_GET['logid']; ?>" />
        
		<!-- Search Title -->
        <?php $FollowedUserLogsListTable->search_box( __( 'Search', 'wpwfp' ), 'wpwfp' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $FollowedUserLogsListTable->display(); ?>
        
    </form>
</div><!--wrap-->