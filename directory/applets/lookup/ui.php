<div class="vbx-applet">
	<h2>Directory lookup</h2>
	
	<p>When the caller reaches this menu, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('intro'); ?>

	<p>
		Search by:
		<?php
			$searchOnSelection = array("first" => "", "last" => "");
			$searchOnSelection[AppletInstance::getValue('search-on', 'last')] = 'selected="true"';
		?>
		<select name="search-on">
			<option value="first" <?php echo $searchOnSelection['first']; ?>>First Name</option>
			<option value="last" <?php echo $searchOnSelection['last']; ?>>Last Name</option>
		</select>
	</p>

	<p>
		How many digits should we collect from the user?
		<input type="text" name="num-digits" value="<?php echo AppletInstance::getValue('num-digits', '4'); ?>" />
	</p>

	<p>If no entries were found, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('not-found'); ?>

	<p>If they make an invalid choice, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('invalid-selection'); ?>

	<p>When the system connects them, they will hear:</p>
	<?php echo AppletUI::audioSpeechPicker('dialing'); ?>
</div>
