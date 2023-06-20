<?php
/**
 * This is the file for the _____demo_____ ACF block type
 *
 * @example https://www.advancedcustomfields.com/resources/acf_register_block_type/#examples
 * 
 * @example https://www.youtube.com/watch?v=s6qomMzP0BU How to make a ACF Block Type
 *
 * @package Coalition_Technologies
 */


// Must include for ct_block_init to work!!
global $ctblock;
$ctblock = $block;

?>

<div <?php ct_block_init( 'demo' ); ?>>
	<?php
		the_field( 'custom-field-name' );
	?>
</div>
