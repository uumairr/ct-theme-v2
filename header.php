<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Coalition_Technologies
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php if ( function_exists( 'the_field' ) ) : ?>
		<?php the_field('header_code', 'option'); ?>
	<?php endif; ?>
</head>
<body>
	<?php wp_body_open(); ?>
	<div id="page">
		<div id="site-wrapper" <?php body_class( 'site' ); ?>>
			<header id="masthead" class="site-header">
				<div class="container">
					<div class="site-branding">
						<a href="<?php echo get_site_url(); ?>">
							<?php ct_logo(); ?>
						</a>
					</div>

					<nav id="site-navigation" class="main-navigation">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'menu_id'        => 'primary-menu',
									'walker'		 => new A11y_Menu_Walker, 
								)
							);
						?>
					</nav>
				</div>
			</header>

			<div id="content" class="site-content">
