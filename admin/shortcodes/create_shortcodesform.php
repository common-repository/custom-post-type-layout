<?php
// GET LIST OF POST TYPES
$args = array(
    'public' => true,
    '_builtin' => false
);
$post_types['post'] = 'post';
$post_types['page'] = 'page';
$post_types         = array_merge($post_types, get_post_types($args));

$args = array(
    'hide_empty' => 1,
    'hierarchical' => 0
);

// ORDER BY ARRAY LIST
$order_by = array(
    'none' => 'None',
    'ID' => 'ID',
    'title' => 'Title',
    'date' => 'Date',
    'comment_count' => 'Comment Count',
);
if(isset($_POST['submit-shortcode'])) {
   if (empty($_POST['vc_sg_shortcode_id'])) {
        $cbw_shortcode_id = get_option('cbw_primary_key');
        update_option('cbw_primary_key', $cbw_shortcode_id + 1);
    } else {
        $cbw_shortcode_id = sanitize_text_field($_POST['vc_sg_shortcode_id']);
    }
    
    $cat_arr = map_deep( wp_unslash( $_POST['post_category'] ), 'sanitize_text_field' );
    $tax_arr = map_deep( wp_unslash( $_POST['tax_input'] ), 'sanitize_text_field' );
    $post_type_name = sanitize_text_field($_POST['vc_sg_post_type']);
    $cbw_shortcodeDataInput = array(
        'vc_sg_shortcode_name' => sanitize_text_field($_POST['vc_sg_shortcode_name']),
        'vc_sg_layout_name' => sanitize_text_field($_POST['vc_sg_layout_name']),
        'vc_sg_columns' => sanitize_text_field(@$_POST['vc_sg_columns']),
        'vc_sg_post_type' => $post_type_name,
        //'vc_sg_post_category' => isset($_POST['post_category'])? $_POST['post_category']:array(),
        'vc_sg_post_category' => @$cat_arr,//@$cat_arr_loop,
        'vc_sg_publish_date' =>  sanitize_text_field($_POST['vc_sg_publish_date']),
        'vc_sg_date_format' =>  sanitize_text_field(@$_POST['vc_sg_date_format']),
        'vc_sg_comment_count' =>  sanitize_text_field($_POST['vc_sg_comment_count']),
        //'vc_sg_tax_input' => sanitize_text_field(@$_POST['tax_input']),
        'vc_sg_tax_input' => @$tax_arr,//@$taxname,
        
        'vc_sg_page_parent' => sanitize_text_field($_POST['vc_sg_page_parent']),
        'vc_sg_image_size' => sanitize_text_field($_POST['vc_sg_image_size']),
        'vc_sg_post_count' => sanitize_text_field($_POST['vc_sg_post_count']),
        'vc_sg_order_by' => sanitize_text_field($_POST['vc_sg_order_by']),
        'vc_sg_order' => sanitize_text_field($_POST['vc_sg_order']),
        'vc_sg_post_feed' => sanitize_text_field($_POST['vc_sg_post_feed']),
        'vc_sg_pagination_style' => sanitize_text_field($_POST['vc_sg_pagination_style']),
        'vc_sg_readmore' => sanitize_text_field(@$_POST['vc_sg_readmore'])
    );

    $cbw_shortcodesRawData = get_option('cbw_shortcodes_data');
    $cbw_shortcodesData    = json_decode($cbw_shortcodesRawData, true);
 
    if (empty($cbw_shortcodesData)):
        $cbw_shortcodesData = array();
    endif;
    

    $cbw_shortcodesData[$cbw_shortcode_id] = $cbw_shortcodeDataInput;
    $cbw_updateshortcodesData              = json_encode($cbw_shortcodesData);
    update_option('cbw_shortcodes_data', $cbw_updateshortcodesData);
    $cbw_shortcodes_msg        = 'Shortcode Saved';
    $cbw_shortcodes_msg_status = 'updated';
       
    $url = site_url('/wp-admin/admin.php?page=vc_list_shortcode');
    echo '<script> window.location="' . esc_url($url) . '"; </script> ';
}
    // EDIT SHORTCODE
  $cbw_shortcodeDetails = '';
  if (isset($_GET['edit_shortcode_key'])) {
      $cbw_shortcodeEditId   = sanitize_text_field($_GET['edit_shortcode_key']);
      $cbw_shortcodesRawData = get_option('cbw_shortcodes_data');
      $cbw_shortcodesData    = json_decode($cbw_shortcodesRawData, true);
      $cbw_shortcodeDetails  = $cbw_shortcodesData[$cbw_shortcodeEditId];
  }

 ?>
 <form action="#" id="cbw_form" method="post">
   <input type="hidden" value="<?php
      echo !empty($cbw_shortcodeDetails) ? esc_html($cbw_shortcodeEditId) : '';
      ?>" id="vc_sg_shortcode_id" name="vc_sg_shortcode_id" />
   <input type="hidden" name="getajaxurl" id="getajaxurl" value="<?php
      echo esc_url(admin_url('admin-ajax.php'));
      ?>">
   <table class="sg_form">
      <tr>
         <td width="170">ShortCode Name</td>
         <td><input type="text" name="vc_sg_shortcode_name" value="<?php
            echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_shortcode_name'));
            ?>" maxlength="50" minlength="5" class="required medium"/></td>
      </tr>
      <tr>
         <td width="170">Layout</td>
         <td>
           <select name="vc_sg_layout_name" class="required medium layout">
               <option value=""> -- Select Layout -- </option>
               <option value="grid" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_layout_name', 'grid'));
                  ?>>Grid</option>
               <option value="list" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_layout_name', 'list'));
                  ?>>List</option>
               <option value="zigzag" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_layout_name', 'zigzag'));
                  ?> disabled>Zigzag (Available in pro)</option>
               <option value="masonry" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_layout_name', 'masonry'));
                  ?> disabled>Masonry (Available in pro)</option>
            </select>
         </td>
      </tr>
      <tr class="gridcolumn" style="display: none;">
         <td>Columns</td>
         <td>
            <select name="vc_sg_columns" class="required medium">
               <option value=""> -- Select -- </option>
               <option value="col1" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_columns', 'col1'));
                  ?>>1 Column</option>
               <option value="col2" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_columns', 'col2'));
                  ?>>2 Columns</option>
               <option value="col3" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_columns', 'col3'));
                  ?>>3 Columns</option>
               <option value="col4" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_columns', 'col4'));
                  ?>>4 Columns</option>
            </select>
         </td>
      </tr>
      <tr>
        <td>Post Type</td>
          <td>
            <select name="vc_sg_post_type" id="vc_sg_post_type" class="required medium" onchange="vc_sg_postcategory();">
               <option value=""> -- Select -- </option>
               <?php
                  foreach ($post_types as $post_type) {
                      $postTypeObj = get_post_type_object($post_type);
                  ?>
               <option value="<?php
                  echo esc_html($post_type);
                  ?>" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_post_type', $post_type));
                  ?>><?php
                  echo esc_html($postTypeObj->labels->name);
                  ?></option>
               <?php
                  }
                  ?>
            </select>
          </td>
      </tr>
      <?php
         unset($post_types['page']);
         foreach ($post_types as $post_type):
             $taxonomy_objects = get_object_taxonomies($post_type, 'objects');
             $exclude = array( 'post_tag', 'post_format' );
             if (!empty($taxonomy_objects)):
                 foreach ($taxonomy_objects as $taxonomy_slug => $taxonomy_object):
                      
                    if ($taxonomy_object->public == 1 && wp_count_terms($taxonomy_slug) > 0 && ($taxonomy_object->name != 'post_tag' && $taxonomy_object->name != 'post_format' )):?>
                        <tr id="" class="taxonomy checklist hidden vc_posttype_taxonomies <?php
                         echo esc_html($post_type);
                         ?>_taxonomies_holder">
                                     <td><?php
                                        echo esc_html($taxonomy_object->labels->name);
                                        ?></td>
                                     <td>
                                        <?php
                                           $selected_terms = array();
                                           //echo $taxonomy_slug;
                                           if ($taxonomy_slug == 'category'):
                                               $selected_terms = !empty($cbw_shortcodeDetails['vc_sg_post_category']) ? $cbw_shortcodeDetails['vc_sg_post_category'] : array();
                                           else:
                                               $selected_terms = !empty($cbw_shortcodeDetails['vc_sg_tax_input'][$taxonomy_slug]) ? $cbw_shortcodeDetails['vc_sg_tax_input'][$taxonomy_slug] : array();
                                           endif;
                                           ?>
                                        <ul>
                                           <?php
                                              $args = array(
                                                  'selected_cats' => $selected_terms,
                                                  'taxonomy' => $taxonomy_slug
                                              );
                                              wp_terms_checklist(0, $args);
                                              ?>
                                        </ul>
                                        <div style="clear:both;"></div>
                                     </td>
                        </tr><?php
                    endif;
                  endforeach;
            endif;
         endforeach;
         ?>
      <tr id="" class="hidden vc_posttype_taxonomies page_taxonomies_holder">
         <td>Child Page of</td>
         <td>
            <?php
               wp_dropdown_pages(array(
                   'class' => 'medium',
                   'name' => 'vc_sg_page_parent',
                   'hierarchical' => true,
                   'show_option_none' => 'None',
                   'option_none_value' => '-1',
                   'selected' => @$cbw_shortcodeDetails['vc_sg_page_parent']
               ));
               ?>
         </td>
      </tr>
      <tr>
          <td>Show Publish Date</td>
          <td>
            <select name="vc_sg_publish_date" class="required medium publishdate">
              <option value="">--select--</option>
              <option value="yes" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_publish_date', 'yes'));
                  ?>>Yes</option>
              <option value="no" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_publish_date', 'no'));
                  ?>>No</option>
            </select>
          </td>
      </tr>
      <tr class="dateformate" style="display: none;">
         <td width="170">Date Format</td>
         <td><input type="text" name="vc_sg_date_format" value="<?php
            echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_date_format'));
            ?>" maxlength="50" minlength="5" placeholder="F j, Y" class="medium"/><a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank"><i>Check Date formate from here</i></a></td>
      </tr>
      <tr>
          <td>Show Comment Count</td>
          <td>
            <select name="vc_sg_comment_count" class="required medium">
              <option value="">--select--</option>
              <option value="yes" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_comment_count', 'yes'));
                  ?>>Yes</option>
              <option value="no" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_comment_count', 'no'));
                  ?>>No</option>
            </select>
          </td>
      </tr>
      <tr>
         <td>Thumbnail Image Size</td>
         <td>
            <?php
               $getimage_sizes = get_intermediate_image_sizes();
               ?>
            <select name="vc_sg_image_size" class="required medium">
               <?php
                  foreach ($getimage_sizes as $size_name => $size_attr):
                  ?>
               <option value="<?php
                  echo esc_html($size_attr);
                  ?>" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_image_size', $size_attr));
                  ?>><?php
                  echo esc_html(ucwords(str_replace(array(
                      '-',
                      '_'
                  ), ' ', $size_attr)));
                  ?></option>
               <?php
                  endforeach;
                  ?>
            </select>
         </td>
      </tr>
      <tr>
         <td>Post Per Page</td>
         <td>
            <input type="number" maxlength="2" name="vc_sg_post_count" value="<?php
               echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_post_count'));
               ?>" class="required small per_page" />(Note: Write -1 for Unlimited)    
         </td>
      </tr>
      <tr>
         <td>Order By</td>
         <td>
            <select name="vc_sg_order_by" class="required medium">
               <option value="0"> Default </option>
               <?php
                  foreach ($order_by as $order_key => $order_value) {
                  ?>
               <option value="<?php
                  echo esc_html($order_key);
                  ?>" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_order_by', $order_key));
                  ?>><?php
                  echo esc_html($order_value);
                  ?></option>
               <?php
                  }
                  ?>
            </select>
         </td>
      </tr>
      <tr>
         <td>Order</td>
         <td>
            <select name="vc_sg_order" class="required medium">
               <option value="0" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_order', '0'));
                  ?>> Default </option>
               <option value="ASC" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_order', 'ASC'));
                  ?>>Ascending</option>
               <option value="DESC" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_order', 'DESC'));
                  ?>>Descending</option>
            </select>
         </td>
      </tr>
      <tr>
         <td>For each post in a feed, include</td>
         <td>
            <select name="vc_sg_post_feed" class="required medium">
              <option value="summary" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_post_feed', 'summary'));
                  ?>>Summary</option>
               <option value="fulltext" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_post_feed', 'fulltext'));
                  ?>>Full Text</option>
               
            </select>
         </td>
      </tr>
      <tr class="pagination">
         <td>Pagination Style</td>
         <td>
            <select name="vc_sg_pagination_style" class="selectpagination required medium">
               <option value=""> -- Select -- </option>
               <option value="number_pagination" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_pagination_style', 'number_pagination'));
                  ?>>Number Pagination</option>
               <option value="ajax_load_btn" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_pagination_style', 'ajax_load_btn'));
                  ?> disabled>Ajax Load More (Available in pro)</option>
               <option value="infinite" <?php
                  echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_pagination_style', 'infinite'));
                  ?> disabled>Infinity Scroll (Available in pro)</option>
            </select>
         </td>
      </tr>
      <tr>
          <td>Read more button text</td>
          <td>
            <input type="text" name="vc_sg_readmore" value="<?php
            echo esc_html(cplayout_fill_up_shorcodeform($cbw_shortcodeDetails, 'vc_sg_readmore'));
            ?>">
          </td>
      </tr>
      <?php 
          $button_name = (isset($_GET['edit_shortcode_key']))? "Update" : "Save";
       ?>
      <tr>
         <td>&nbsp;</td>
         <td><input type="submit" name="submit-shortcode" class="button-primary small" value="<?php echo esc_html($button_name); ?>" /></td>
      </tr>
   </table>
</form>
<script>
function vc_sg_postcategory(){
  post_type = jQuery('#vc_sg_post_type').val();          
  jQuery('.vc_posttype_taxonomies').slideUp();
  jQuery('.'+post_type+'_taxonomies_holder').slideDown();
}
/*
** onload jquery function
*/
  vc_sg_postcategory();
</script>