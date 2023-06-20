<?php

/**
 * Setup of the welcome panel.
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );

add_action( 'welcome_panel', function() { ?>
	<style>.welcome-panel::before{display:none;}.welcome-panel{overflow:hidden;}.welcome-panel-close,[for="wp_welcome_panel-hide"]{display:none!important;}</style>
	<iframe src="https://ct-wp-widget.pages.dev/wp-widget" frameborder="0" width="100%" id="ct-sales"></iframe>
	<script type="text/javascript">var eventMethod=window.addEventListener?'addEventListener':'attachEvent',eventer=window[eventMethod],messageEvent=eventMethod=='attachEvent'?'onmessage':'message',widget=document.getElementById('ct-sales');eventer(messageEvent,function(e){if(isNaN(e.data))return;widget.style.height=e.data+'px'},!1);widget.addEventListener('load',function(){widget.contentWindow.postMessage('ct_host','*')});if(!document.getElementById('wp_welcome_panel-hide').checked){document.getElementById('wp_welcome_panel-hide').click()}</script>
<?php } );
