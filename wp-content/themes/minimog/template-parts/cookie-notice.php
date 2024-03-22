<?php
defined( 'ABSPATH' ) || exit;

$button_text = Minimog::setting( 'notice_cookie_button_text' );
?>
<div id="cookie-notice-popup" class="cookie-notice-popup close">
	<div class="cookie-messages">
		<?php echo Minimog::setting( 'notice_cookie_messages' ); ?>
	</div>
	<a id="btn-accept-cookie"
	   class="tm-button tm-button-xs style-flat"><?php echo '' . $button_text; ?></a>
</div>
