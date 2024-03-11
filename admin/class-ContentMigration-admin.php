<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    ContentMigration
 * @subpackage ContentMigration/admin
 * @author     Ramandeep Singh <raman@insteptechnologies.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class ContentMigration_Admin
{
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ContentMigration-admin.css', array(), $this->version, 'all');
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script('boot3', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array(
            'bootstarp'
        ), $this->version, false);
        // wp_enqueue_script('validate', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(
        //     'jquery'
        // ) , $this->version, false);
        // wp_enqueue_script('validatejs', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js', array(
        //     'validate'
        // ) , $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ContentMigration-admin.js', array(
            'jquery'
        ), $this->version, false);
    }
    
    public function loadLayout()
    {
        require plugin_dir_path(__FILE__) . '/admin/html/index.php';
    }
    
    /** Getting Records from last 24 hours */
    public static function getingRecords($post = null)
    {
        global $wpdb, $table_prefix;
        $table_name = $table_prefix . 'posts';
        if (isset($post)) {
            $startDate = $post['startdate'];
            $endDate   = $post['enddate'];
        } else {
            $startDate = date("Y-m-d H:m:s", strtotime('-24 hours'));
            $endDate   = date("Y-m-d H:m:s");
        }
        $total          = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE (post_type='page' || post_type='product') && CAST(post_modified AS DATE) >= '$startDate' && CAST(post_modified AS DATE) <= '$endDate'");
        $items_per_page = 10;
        $page           = isset($_GET['cpage']) ? abs((int) $_GET['cpage']) : 1;
        $offset         = ($page * $items_per_page) - $items_per_page;
        $result         = $wpdb->get_results("SELECT * FROM $table_name where (post_type='page' || post_type='product') && CAST(post_modified AS DATE) >= '$startDate' && CAST(post_modified AS DATE) <= '$endDate' GROUP BY post_title, post_status ORDER BY post_modified DESC LIMIT $offset, $items_per_page ");
        $totalPage      = ceil($total / $items_per_page);
        return $response = array(
            "results" => $result,
            "totalPage" => $totalPage,
            "page" => $page
        );
    }
    
    /* Insert Records to another database if not exists before */
    
    public static function insertingRecords($data)
    {
        
        global $wpdb, $table_prefix;
        $id      = $data['id'];
        $implode = implode(',', $id);
        $implode;
        $table_name     = $table_prefix . 'posts';
        $result         = $wpdb->get_results("SELECT * FROM $table_name where id IN($implode)");
        $mydb           = ContentMigration_Admin::gettingDbConnectedInstance();
        $newConnection  = ContentMigration_Admin::getingDatabaseDetail();
        $inserted_ids   = array();
        
        if ($mydb && $newConnection) {
            foreach ($result as $object) {

                //replace guid with target url			
				$target_site_url = $newConnection->site_url;
				$old_guid = $result[$i]->guid;
				$old_site_url = get_site_url();
                $new_guid = str_replace($old_site_url, $target_site_url, $old_guid);
                
                $post_author           = $object->post_author;
                $post_date             = $object->post_date;
                $post_date_gmt         = $object->post_date_gmt;
                $post_content          = $object->post_content;
                $text                  = $object->post_content;
                $post_title            = $object->post_title;
                $post_excerpt          = $object->post_excerpt;
                $post_status           = $object->post_status;
                $comment_status        = $object->comment_status;
                $ping_status           = $object->ping_status;
                $post_name             = $object->post_name;
                $to_ping               = $object->to_ping;
                $pinged                = $object->pinged;
                $post_modified_date    = $object->post_modified;
                $post_modified_gmt     = $object->post_modified_gmt;
                $post_content_filtered = $object->post_content_filtered;
                $post_parent           = $object->post_parent;
                $guid                  = $new_guid;
                $menu_order            = $object->menu_order;
                $post_type             = $object->post_type;
                $post_mime_type        = $object->post_mime_type;
                $comment_count         = $object->comment_count;
                $rows                  = $mydb->get_results("SELECT * FROM `$table_name` where `post_title` = '".$object->post_title."'");
                if (!is_null($rows) && count($rows) > 0) {
                    ContentMigration_Admin::updateExistingPageContent($rows, $object, $mydb);
                } else {
                    $result1 = $mydb->insert($table_name, array(
                        'post_author' => $post_author,
                        'post_date' => $post_date,
                        'post_date_gmt' => $post_date_gmt,
                        'post_content' => $text,
                        'post_title' => $post_title,
                        'post_excerpt' => $post_excerpt,
                        'post_status' => $post_status,
                        'comment_status' => $comment_status,
                        'ping_status' => $ping_status,
                        'post_name' => $post_name,
                        'to_ping' => $to_ping,
                        'pinged' => $pinged,
                        'post_modified' => $post_modified_date,
                        'post_modified_gmt' => $post_modified_gmt,
                        'post_content_filtered' => $post_content_filtered,
                        'post_parent' => $post_parent,
                        'guid' => $guid,
                        'menu_order' => $menu_order,
                        'post_type' => $post_type,
                        'post_mime_type' => $post_mime_type,
                        'comment_count' => $comment_count
                    ));
                    if ($result1 > 0) {
                        
                        $postid         = $mydb->insert_id;
                        $table_name_new = $table_prefix . 'icl_translations';
                        $get_trid_query = $mydb->get_results("SELECT `trid` FROM $table_name_new ORDER BY trid DESC LIMIT 1");
                        $last_trid      = $get_trid_query[0]->trid;
                        $insert_lg      = $mydb->insert($table_name_new, array(
                            'element_type' => 'post_page',
                            'element_id' => $postid,
                            'trid' => $last_trid + 1,
                            'language_code' => 'en',
                            'source_language_code' => NULL
                        ));
                        ContentMigration_Admin::insertingMetaFields($object->ID, $postid); //inserting meta in this
                        $inserted_ids[] = $postid;
                    }
                }
            }
            $target_records = count($inserted_ids);
            $origin_records = count($result);
            if ($target_records === $origin_records) {
                $response = array(
                    "status" => "Success",
                    "Message" => "Pages Moved Successfully.",
                    "pages_count" => $target_records
                );
                return $response;
            }
        } else {
            ContentMigration_Admin::showError("Error Occured While Connecting to Database Please Try Again Later.");
            exit();
        }
    }
    public static function updateExistingPageContent($rows = null, $object = null, $mydb = null)
    {
        global $table_prefix;
        $table_name = $table_prefix . 'posts';
        if ($mydb) {
            $data        = $mydb->last_result;
            $newUpdateID = $data[0]->ID;

            $update = $mydb->update($table_name, array(
                'post_content' => $object->post_content,
                'post_title' => $object->post_title,
                'post_status' => $object->post_status
            ), array(
                'post_title' => $object->post_title
            ));
            
            if ($update > 0) {
                ContentMigration_Admin::updateMetaFields($object->ID, $newUpdateID);
                return $responses = array(
                    "status" => "Update",
                    "Message" => "Pages Updated Successfully."
                );
            } else {
                ContentMigration_Admin::showError("Page Already Exists Please Update First");
                exit();
            }
        } else {
            ContentMigration_Admin::showError("Error Occured While Connecting to Database Please Try Again Later.");
            exit();
        }
    }
    
    
    /*Update MetaField */
    
    public static function updateMetaFields($ID, $newUpdateID)
    {
        global $wpdb, $table_prefix;
        $table_name = $table_prefix . 'postmeta';
        $result     = $wpdb->get_results("SELECT * FROM $table_name where post_id =$ID");
        $mydb       = ContentMigration_Admin::gettingDbConnectedInstance();
        if ($mydb) {
            
            if (count($result) > 0) {
                $results = json_decode(json_encode($result), true);
                for ($i = 0; $i < count($results); $i++) {
                    
                    $meta_key   = $results[$i]['meta_key'];
                    $meta_value = $results[$i]['meta_value'];
                    $update     = $mydb->query("UPDATE `$table_name` SET `meta_value` = '$meta_value' WHERE `post_id`= '$newUpdateID' and `meta_key`= '$meta_key'");
                }
            }
        } else {
            ContentMigration_Admin::showError("Error Occured While Connecting to Database Please Try Again Later.");
            exit();
        }
    }
    
    /*Insert MetaField */
    
    public static function insertingMetaFields($ID, $newpostid)
    {
        global $wpdb, $table_prefix;
        $table_name = $table_prefix . 'postmeta';
        $result     = $wpdb->get_results("SELECT * FROM $table_name where post_id ='$ID'");
        $mydb       = ContentMigration_Admin::gettingDbConnectedInstance();
        if ($mydb) {
            if (count($result) > 0) {
                foreach ($result as $object):
                    $table_name = $table_prefix . 'postmeta';
                    $result     = $mydb->insert($table_name, array(
                        'post_id' => $newpostid,
                        'meta_key' => $object->meta_key,
                        'meta_value' => $object->meta_value
                    ));
                endforeach;
            } else {
                return true;
            }
        } else {
            ContentMigration_Admin::showError("Error Occured While Connecting to Database Please Try Again Later.");
            exit();
        }
    }
    
    public static function gettingDbConnectedInstance()
    {
        $newConnection = ContentMigration_Admin::getingDatabaseDetail();
        if (isset($newConnection) && $newConnection != null && $newConnection != "") {
            return $mydb = new wpdb($newConnection->db_username, $newConnection->db_passworsd, $newConnection->db_name, $newConnection->db_host);
        } else {
            ContentMigration_Admin::showError("No Database Connected Please Add Database Details in Settings.");
            exit();
        }
    }
    
    /* Insert database Detail*/
    
    public static function insertingDatabaseDetail($detail)
    {
        global $wpdb, $table_prefix;
        $email      = $detail['ëmail'];
        $dbname     = $detail['dbname'];
        $host       = $detail['host'];
        $uname      = $detail['üname'];
        $pwd        = $detail['pwd'];
        $site_url   = $detail['site_url'];
        $table_name = $table_prefix . 'content_migration_settings';
        $insert     = $wpdb->query("INSERT INTO $table_name(`auth_email`, `db_host`, `db_name`, `db_username`, `db_passworsd`, `site_url`) VALUES('$email', '$host', '$dbname', '$uname', '$pwd', '$site_url')");
        if ($insert > 0) {
            return $msg = array(
                "status" => "Success",
                "Message" => "Settings Saved Successfully."
            );
        }
    }
    
    /*Getting Database Detail */
    
    public static function getingDatabaseDetail()
    {
        global $wpdb, $table_prefix;
        $table_name = $table_prefix . 'content_migration_settings';
        $result     = $wpdb->get_row("SELECT * FROM $table_name");
        
        return $result;
    }
    
    /*Update Database Detail */
    
    public static function UpdateDatabaseDetail($data)
    {
        global $wpdb, $table_prefix;
        $id         = $data['id'];
        $email      = $data['ëmail'];
        $dbname     = $data['dbname'];
        $host       = $data['host'];
        $uname      = $data['üname'];
        $pwd        = $data['pwd'];
        $site_url   = $data['site_url'];
        $table_name = $table_prefix . 'content_migration_settings';
        $update     = $wpdb->query("UPDATE $table_name SET `auth_email`='$email', `db_host`='$host', `db_name` = '$dbname', `db_username` = '$uname', `db_passworsd` = '$pwd', `site_url` = '$site_url' WHERE id='$id'");
        if ($update > 0) {
            return $update_msg = array(
                "status" => "Success",
                "Message" => "Settings Updated Successfully."
            );
        } else {
            return $update_msg = array(
                "status" => "Error",
                "Message" => "Make Any Change For Update."
            );
        }
    }
    
    /*Get Success Message */
    
    public static function showSccess($text)
    {
?>
       <div class="updated notice">
            <p><?php
        echo $text;
?></p>
        </div>
    <?php
    }
    
    /*Get Update Success Message */
    
    public static function updateSccess($text)
    {
?>
       <div class="updated notice">
            <p><?php
        echo $text;
?></p>
        </div>
    <?php
    }
    /*Get Error Message */
    
    public static function showError($text)
    {
?>
   <div class="error notice">
        <p><?php
        echo $text;
?></p>
    </div>
<?php
    }
}
?>