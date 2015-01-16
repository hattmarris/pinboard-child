<?php
/*
Template Name: Contact
*/
?><?php get_header(); ?>
	<?php if( is_front_page() ) : ?>
		<?php if( pinboard_get_option( 'slider' ) ) : ?>
			<?php get_template_part( 'slider' ); ?>
		<?php endif; ?>
		<?php get_sidebar( 'wide' ); ?>
		<?php get_sidebar( 'boxes' ); ?>
	<?php endif; ?>
	<div id="container">
		<section id="content" <?php pinboard_content_class(); ?>>
			<?php if( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<div class="entry">
						<header class="entry-header">
							<<?php pinboard_title_tag( 'post' ); ?> class="entry-title"><?php the_title(); ?></<?php pinboard_title_tag( 'post' ); ?>>
						</header><!-- .entry-header -->
						<div class="entry-content">				
							<?php
							if($_POST['submit'] == 'Submit') {
								//prevent injection
								if (!isset( $_POST['contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['contact_form_nonce'], 'submit_contact_form' ) ) {
									print 'Sorry, your nonce did not verify.';
									exit;
								}
								//prevent spam
								if(isset($_POST['foo']) && trim($_POST['foo']) != '') {
									print 'Sorry, your submission has failed.';
									exit;
								} 
								//process form data
								else {
									$name = $_POST['contact-form-name'];
									$email = $_POST['contact-form-email'];
									$mess = $_POST['contact-form-message'];
									$message = "<p>".$name."</p><p>".$email."</p><p>".$mess."</p>";
									if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
										$subject = 'Contact Form Submission on tallenwood.com';
										$to = get_option('admin_email');
										add_filter( 'wp_mail_content_type', 'set_html_content_type' );
											function set_html_content_type() {
												return 'text/html';
											}
										wp_mail( $to, $subject, $message );
										// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
										remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
										echo 'Thank you. Your message has been successfully submitted.';
									} else {
										echo '<style>.contact-form-input-email label {color: #FF0000; font-weight: bold;} .contact-form-input-email label:before {content: "*";} .contact-form-input-email:after {content: "Please enter a valid email address";}</style>';
									}
								}
							} else {
							?>
							<form method="post">
								<div class="contact-form-input-name">
									<label for="contact-form-name">Full Name:</label>
										<br>
										<input id="contact-form-name" name="contact-form-name" type="text" />
								</div>
								<div class="contact-form-input-email">
									<label for="contact-form-email">Email:</label>
										<br>
										<input id="contact-form-email" name="contact-form-email" type="text" />
								</div>
								<div class="contact-form-input-message">
									<label for="contact-form-message">Message:</label>
										<br>
										<textarea rows="4" cols="100" id="contact-form-message" name="contact-form-message"></textarea>
								</div>
								<br>
								<?php wp_nonce_field('submit_contact_form','contact_form_nonce'); ?>
								<div id="fooDiv">
									<label for="foo">Leave this field blank</label>
										<input type="text" name="foo" id="foo">
								</div>
									<script>
									(function () {
									    var e = document.getElementById("fooDiv");
									    e.parentNode.removeChild(e);
									})();
									</script>
								<input type="submit" id="submit" name="submit" value="Submit"></input>
							</form>
							<?php } ?>
							<?php the_content(); ?>
							<div class="clear"></div>
						</div><!-- .entry-content -->
						<?php wp_link_pages( array( 'before' => '<footer class="entry-utility"><p class="post-pagination">' . __( 'Pages:', 'pinboard' ), 'after' => '</p></footer><!-- .entry-utility -->' ) ); ?>
					</div><!-- .entry -->
					<?php comments_template(); ?>
				</article><!-- .post -->
			<?php else : ?>
				<?php pinboard_404(); ?>
			<?php endif; ?>
		</section><!-- #content -->
		<?php if( ( 'no-sidebars' != pinboard_get_option( 'layout' ) ) && ( 'full-width' != pinboard_get_option( 'layout' ) ) ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
		<div class="clear"></div>
	</div><!-- #container -->
<?php get_footer(); ?>