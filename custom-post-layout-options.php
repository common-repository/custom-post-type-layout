<?php
add_action('admin_menu','cplayoutcreatemenu'); 
add_action('admin_enqueue_scripts', 'cplayoutsgscripts');
add_action('wp_enqueue_scripts', 'cplayoutsgfrontcssscripts');
if (!function_exists('cplayoutcreatemenu')) {
function cplayoutcreatemenu(){
    add_menu_page('Custom Post Type Layout','Custom Post Type Layout','manage_options','vc_list_shortcode','cplayoutlistshortcode');
    add_submenu_page('vc_list_shortcode','Add New Custom Post Type Layout','Add New Layout','manage_options','vc_create_shortcodes','cplayoutlcreateshortcodes');
}
}
/*
** List of shortcode
*/
if (!function_exists('cplayoutlistshortcode')) {
    function cplayoutlistshortcode() {
        include('admin/shortcodes/list_shortcodes.php');
    }
}

if (!function_exists('cplayout_fill_up_shorcodeform')) {
    function cplayout_fill_up_shorcodeform($fillUpData, $fieldkey, $selectOptionValue = ''){
        if (!empty($fillUpData)){
            if (array_key_exists($fieldkey,$fillUpData)){
                if (empty($selectOptionValue)){
                    return $fillUpData[$fieldkey];
                } else {
                    if ($fillUpData[$fieldkey] == $selectOptionValue){
                        return 'selected="selected"';
                    } else {
                        return '';
                    }
                }
            }
        }
        return '';
    }
}

/*
** create shortcode form
*/
if (!function_exists('cplayoutlcreateshortcodes')) {
    function cplayoutlcreateshortcodes() {
        include_once('admin/shortcodes/create_shortcodesform.php');
    }
}
/*
** load css and js for fronted
*/
if (!function_exists('cplayoutsgfrontcssscripts')) {
    function cplayoutsgfrontcssscripts() {
        wp_enqueue_style( 'vc_sg_fronted_css',plugin_dir_url( __FILE__ ).'css/sg-fronted-style.css', array(),'', 'all' );
    }
}
/*
** load css and js for admin
*/
if (!function_exists('cplayoutsgscripts')) {
    function cplayoutsgscripts() {
        wp_enqueue_style( 'vc_sg_shortcode_table',plugin_dir_url( __FILE__ ).'admin/css/shortcode-style.css', array(),'', 'all' );
        wp_register_script('vc_sg_validate_js',plugin_dir_url( __FILE__ )."admin/js/jquery.validate.min.js",array(),false,true);  
        wp_enqueue_script('vc_sg_validate_js');
        wp_register_script('vc_sg_formvalidate',plugin_dir_url( __FILE__ )."admin/js/formvalidate.js",array(),false,true); 
        wp_enqueue_script('vc_sg_formvalidate');
    }
}
/*
** Display shortcode in template
*/
add_shortcode( 'vcpost', 'cplayoutpostnormalpaginationshortcode');
if (!function_exists('cplayoutpostnormalpaginationshortcode')) {
    function cplayoutpostnormalpaginationshortcode($atts){
        global $wp_query;
        if(array_key_exists('id', $atts)) {
            $pageNumber = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $shortcodeId = $atts['id'];
            if(!empty($shortcodeId)) {
                $shortcode_option_datas  = get_option('cbw_shortcodes_data');
                $get_json_datas     = json_decode($shortcode_option_datas, true);
                if(array_key_exists($shortcodeId, $get_json_datas)) {
                    $get_json_Data = $get_json_datas[$shortcodeId];

                    $query_arg = array(
                        'post_type'         =>  $get_json_Data['vc_sg_post_type'],
                        'posts_per_page'    =>  $get_json_Data['vc_sg_post_count'],
                        'post_status'       =>  'publish'
                    );
                    if(($get_json_Data['vc_sg_post_type'] == "page") && $get_json_Data['vc_sg_page_parent'] > 0) {
                        $query_arg['post_parent'] =  $get_json_Data['vc_sg_page_parent'];
                    } else {
                        $taxonomy_names = get_object_taxonomies( $get_json_Data['vc_sg_post_type'], 'names');
                        foreach($taxonomy_names as $taxonomy_name) {
                            if($taxonomy_name == "category") {
                               if(!empty($get_json_Data['vc_sg_post_category'])) {
                                    $query_arg['cat'] = $get_json_Data['vc_sg_post_category'];
                               } } else {
                                    if(!empty($get_json_Data['vc_sg_tax_input'][$taxonomy_name])) {
                                        $query_arg['tax_query'][] = array (
                                            'taxonomy'  =>  $taxonomy_name,
                                            'field'     =>  'term_id',
                                            'terms'     =>  $get_json_Data['vc_sg_tax_input'][$taxonomy_name]
                                        );
                                    }
                                }
                            
                        }
                    }
                    if($get_json_Data['vc_sg_order_by'] != '0') {
                        $query_arg['orderby'] = $get_json_Data['vc_sg_order_by'];
                    }
                    if($get_json_Data['vc_sg_order'] != '0') {
                        $query_arg['order'] = $get_json_Data['vc_sg_order'];
                    }
                    if($pageNumber > 0) {
                        $query_arg['paged'] = $pageNumber;
                    }
                    $pagination_style = $get_json_Data['vc_sg_pagination_style'];
                    $col = $get_json_Data['vc_sg_columns'];
                    if($col == "col1"){
                        $col_num  =   12;
                    } else if($col == "col2"){
                        $col_num  =   6;
                    } else if($col == "col3") {
                        $col_num  =   4;
                    } else if($col == "col4") {
                        $col_num  =   3;
                    } else {
                        $col_num = "";
                    }
                    $layout_name = $get_json_Data['vc_sg_layout_name'];
                    $i = 1;
                    $per_page = $get_json_Data['vc_sg_post_count'];

                   
                    if($pagination_style == "number_pagination" || empty($pagination_style) ) {
                        query_posts($query_arg);
                        if (have_posts()):
                            ob_start();
                            echo '<div class="number-pagination alignwide vc-'.esc_html($layout_name).'" >';
                            echo '<div class="flex-row">';
                            while (have_posts()) : the_post(); 
                                include('includes/number-pagination.php');
                            endwhile;
                            echo '</div></div>';
                            $total_pages = $wp_query->max_num_pages;
                            if(function_exists("cplayoutpagination")) {          
                                cplayoutpagination($wp_query->max_num_pages);
                            }
                            wp_reset_query();

                        endif;
                    }
                } else {
                    echo "Could not find this id in this shortcode";
                }

            } else {
                echo "Empty shortcode";
            }
        } else {
            echo "This shortcode is not exist";
        }

        $myvariable = ob_get_clean();
        return $myvariable;
        ob_end_clean();
        //wp_reset_query();
    }
}

// numbered pagination
if (!function_exists('cplayoutpagination')) {
    function cplayoutpagination($pages = '', $range = 4)
    {  
         $showitems = ($range * 2)+1;  
     
         global $paged;
         if(empty($paged)) $paged = 1;
     
         if($pages == '')
         {
             global $wp_query;
             $pages = $wp_query->max_num_pages;
             if(!$pages)
             {
                 $pages = 1;
             }
         }   
     
         if(1 != $pages)
         {
             echo "<div class=\"vc-pagination alignwide\"><span>Page ".esc_html($paged)." of " .esc_html($pages)."</span>";
             if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
             if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
     
             for ($i=1; $i <= $pages; $i++)
             {
                 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                 {
                     echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
                 }
             }
     
             if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";  
             if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
             echo "</div>\n";
         }
    }
}
// excerpt return false
add_filter('excerpt_more', '__return_false',20,1);