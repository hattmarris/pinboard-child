<?php 

add_action( 'wp_enqueue_scripts', 'pinboardchild_enqueue_styles' );
function pinboardchild_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
}

//Remove Comments
// Removes from admin menu
add_action( 'admin_menu', 'my_remove_admin_menus' );
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 9999);

function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
// Removes from admin bar
function mytheme_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

// customize pinboard_social_bookmarks function
if ( ! function_exists( 'pinboard_social_bookmarks' ) ) :
/**
 * Display social networks share icons
 *
 * @since Pinboard 1.0
 */
function pinboard_social_bookmarks() {
	if( pinboard_get_option( 'facebook' ) || pinboard_get_option( 'twitter' ) || pinboard_get_option( 'google' ) || pinboard_get_option( 'pinterest' ) ) : ?>
		<div class="social-bookmarks">
			<?php if( pinboard_get_option( 'facebook' ) ) : ?>
				<div class="facebook-like">
					<div id="fb-root"></div>
					<script>
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="110" data-show-faces="false" data-font="arial"></div>
				</div><!-- .facebook-like -->
			<?php endif; ?>
			<?php if( pinboard_get_option( 'twitter' ) ) : ?>
				<div class="twitter-button">
					<a href="<?php echo esc_url( 'https://twitter.com/share' ); ?>" class="twitter-share-button" data-url="<?php the_permalink(); ?>">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div><!-- .twitter-button -->
			<?php endif; ?>
			<?php if( pinboard_get_option( 'google' ) ) : ?>
				<div class="google-plusone">
					<div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>
					<script type="text/javascript">
						(function() {
							var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							po.src = 'https://apis.google.com/js/plusone.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						})();
					</script>
				</div><!-- .google-plusone -->
			<?php endif; ?>
			<?php if( pinboard_get_option( 'pinterest' ) ) :
				if( wp_attachment_is_image( get_the_ID() ) || has_post_thumbnail() )
					$thumb = wp_get_attachment_image_src( ( is_attachment() ? get_the_ID() : get_post_thumbnail_id() ), 'full' );
				else
					$thumb = pinboard_get_first_image(); ?>
				<div class="pinterest-button">
					<a href="<?php echo esc_url( 'http://pinterest.com/pin/create/button/?url=' . urlencode( get_permalink() ) . ( false !== $thumb ? '&media=' . urlencode( $thumb[0] ) : '' ) . '&description=' . urlencode( apply_filters('the_excerpt', get_the_excerpt() ) ) ); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
					<script>
						(function(d, s, id) {
							var js, pjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//assets.pinterest.com/js/pinit.js";
							pjs.parentNode.insertBefore(js, pjs);
						}(document, 'script', 'pinterest-js'));
					</script>
				</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div><!-- .social-bookmarks -->
	<?php endif;
}
endif;

?>
