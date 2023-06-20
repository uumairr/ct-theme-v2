<?php

function ct_register_sidebars() {

// Register main navigation area
	register_nav_menus( array(
		'primary' => 'Primary',
	) );

// Register widget areas
	register_sidebar(
		array(
			'name'          => 'Footer Column 1',
			'id'            => 'footer-1',
			'description'   => 'Add widgets here.',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="footer-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'Footer Column 2',
			'id'            => 'footer-2',
			'description'   => 'Add widgets here.',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="footer-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'Footer Column 3',
			'id'            => 'footer-3',
			'description'   => 'Add widgets here.',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="footer-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'Footer Column 4',
			'id'            => 'footer-4',
			'description'   => 'Add widgets here',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="footer-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'Footer Column 5',
			'id'            => 'footer-5',
			'description'   => 'Add widgets here',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="footer-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Shop page',
			'id'            => 'shop-sidebar',
			'description'   => 'Add widgets here.',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="sidebar-title">',
			'after_title'   => '</h4>',
		)
	);

}
add_action( 'init', 'ct_register_sidebars' );
