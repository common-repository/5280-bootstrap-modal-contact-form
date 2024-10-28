<?php
class Sbmcf_List_Table extends WP_List_Tablesbmm {
    

    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'message',     //singular name of the listed records
            'plural'    => 'messages',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
    **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'sbmcfname':
            case 'sbmcfemail':
            case 'sbmcfsubject':
            case 'sbmcfmessage':
            case 'sbmcftime':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }


    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'.
     **************************************************************************/
    function column_title($item){
        $sbmcf_nonce = wp_create_nonce('sbmcf_nonce');
        //Build row actions
        $actions = array(
         //   'edit'      => sprintf('<a href="?page=%s&action=%s&message=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&message=%s&_wpnonce=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID'],$sbmcf_nonce)
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['ID'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }


    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="messages[]" value="%s" />', $item['ID']
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'ID',
             'sbmcftime'     => 'Date/Time',
            'sbmcfname'    => 'Name',
            'sbmcfemail'  => 'Email',
             'sbmcfsubject'  => 'Subject',
              'sbmcfmessage'  => 'Message'
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here.
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            //true means it's already sorted
             'sbmcftime'     => array('sbmcftime',false) 
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them.
     **************************************************************************/
function get_bulk_actions() {
    $actions = array(
        'bulk-delete' => 'Delete'
    );
    return $actions;
}

    /** ************************************************************************
     * Optional.
     **************************************************************************/
    function process_bulk_action() {

   $action = $this->current_action();

        //Detect when a bulk action is being triggered...

        if('delete'=== $action) {
          
if (isset($_GET['message'])) {

            $my_record = esc_sql($_GET['message']);
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (! wp_verify_nonce( $nonce, 'sbmcf_nonce')) {
                die( 'Sorry, no unauthorized access!' );
            }
            else {
                self::delete_customer($my_record);
                $this->sbmm_notice_delete('Message deleted.');
            }

            }
        }


  if('bulk-delete'=== $action) {
    if (isset($_GET['messages'])) {
  $ids = $_GET['messages'];
                foreach ($ids as $id) {
                    $idz = esc_sql($id);
                    self::delete_customer($idz);
                  }

  $this->sbmm_notice_delete('Message(s) deleted.');

}

}


}


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. 
     **************************************************************************/
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. 
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. 
         */

        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */

        $this->process_bulk_action();
        
        $data = $this->fetch_table_data();
      
         /**
         * REQUIRED for pagination. 
         */

        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. 
         */

        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to achieve this.
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


    /** custom */

    function fetch_table_data() {

global $wpdb;
      $wpdb_table = $wpdb->prefix . 'sbmcf_messages';		
      $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'ID';
      $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';
      $user_query = "SELECT 
                     ID,sbmcftime,sbmcfname,sbmcfemail,sbmcfsubject,sbmcfmessage
                     FROM 
                     $wpdb_table 
                     ORDER BY $orderby $order";
      // query output_type will be an associative array with ARRAY_A.
      $query_results = $wpdb->get_results( $user_query, ARRAY_A  );
      
      // return result array to prepare_items.
      return $query_results;	

}


function delete_customer($id) {
        global $wpdb;
          $table_name = $wpdb->prefix . 'sbmcf_messages';
        $wpdb->delete(
            $table_name,
            ['ID' => $id],
            ['%d']
        );
    }


function sbmm_notice_delete($myaction) {
    $this_action = $myaction;
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e($this_action, '5280-bootstrap-modal-messenger' ); ?></p>
    </div>
    <?php
}


function no_items() {
    _e( 'No messages found.' );
}


}