<?php
global $post;
$col = $get_json_Data['vc_sg_columns'];
$row_column = array();
$thumb_size = $get_json_Data['vc_sg_image_size'];

if ($col == "col1") {
	$col_class	=	"col-12";
} else if ($col == "col2") {
	$col_class	=	"col-6";
} else if ($col == "col3") {
	$col_class	=	"col-4";
} else if ($col == "col4") {
	$col_class	=	"cols-3";
} else {
	$col_class = "";
}
$readmore_text = $get_json_Data['vc_sg_readmore'];
$publish_date = $get_json_Data['vc_sg_publish_date'];
$date_format  = $get_json_Data['vc_sg_date_format'];
$comment_count = $get_json_Data['vc_sg_comment_count'];
$post_feed = $get_json_Data['vc_sg_post_feed'];

if (!empty($date_format) && $publish_date == 'yes') {
	$display_date = $date_format;
} else {
	$display_date = get_option('date_format');
}
$layout_name = $get_json_Data['vc_sg_layout_name']; ?>

<div class="<?php echo ($layout_name == "grid") ? esc_html($col_class) : 'datalist' ?> item" data-num="<?php echo esc_html($i); ?>">
	<?php if (has_post_thumbnail()) { ?>
		<div class="left thumbnailimage">
				<?php the_post_thumbnail($thumb_size); ?>
		</div>
	<?php } ?>
	<div class="rightcol">
		<div class="post-title">
			<?php if ($post_feed == "summary") { ?>
				<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
			<?php } else { ?>
				<?php the_title(); ?>
			<?php } ?>
		</div>
		<div class="meta-data">
			<?php if (!empty($display_date) && $publish_date == 'yes') { ?>
				<div class="date"><?php echo esc_html(get_the_date($display_date)); ?></div>
			<?php } ?>
			<?php if (!empty($comment_count == 'yes') && $post->comment_count > 0) { ?>
				<div class="comment-count"><?php echo esc_html($post->comment_count); ?> Comments</div>
			<?php } ?>
		</div>
		<div class="post-description">
			<?php if ($post_feed == "summary") { ?>
				<?php the_excerpt(); ?>
			<?php } else { ?>
				<?php the_content(); ?>
			<?php } ?>
			<?php if ((!empty($readmore_text)) && ($post_feed == "summary")) { ?>
				<span class="readmore">
					<a href="<?php echo get_permalink(); ?>"><?php echo esc_html($readmore_text); ?>
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 492.004 492.004" style="enable-background:new 0 0 492.004 492.004;" xml:space="preserve">
							<g>
								<path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
			c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
			v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
			c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
			l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z" />
							</g>
						</svg>
					</a>
				</span>
			<?php } ?>
		</div>
	</div>
</div>
<?php
$i++;
?>