<div class="vbx-applet">
	<h2>Extension dialer</h2>
	
	<p>When the caller reaches this menu, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('intro'); ?>

	<p>If they select an extension which is not in use, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('not-found'); ?>

	<p>When the system connects them, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('dialing'); ?>
</div>
