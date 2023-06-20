<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Coalition_Technologies
 */

?>

			</div><!-- #content -->

			<footer id="colophon" class="site-footer">
				<div class="main-footer">
					<div class="container">
						<?php if ( is_active_sidebar( 'footer-1' ) ) { ?>
							<div class="above-footer">
								<?php dynamic_sidebar( 'footer-1' ); ?>
							</div>
						<?php } if ( is_active_sidebar( 'footer-1' ) && is_active_sidebar( 'footer-2' ) ) { ?>
							<hr>
						<?php } if ( is_active_sidebar( 'footer-2' ) ) { ?>
							<div class="below-footer">
								<?php dynamic_sidebar( 'footer-2' ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="copyright">
					<div class="container">
						<?php if ( function_exists( 'get_field' ) ) : ?>
							&copy; <?= date( 'Y' ) . ' ' . get_field( 'copyright', 'option' ); ?>
						<?php endif; ?>
					</div>
				</div><!-- .site-info -->
			</footer><!-- #colophon -->
		</div><!-- #site-wrapper -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
	<?php if ( function_exists( 'the_field' ) ) : ?>
		<?php the_field('footer_code', 'option'); ?>
	<?php endif; ?>
	</body>
</html>
