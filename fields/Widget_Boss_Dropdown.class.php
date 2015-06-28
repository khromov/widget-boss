<?php
/*
 * Provides a choosable list of posts. Optionally takes a custom WP_Query argument.
 */
class Widget_Boss_Dropdown extends Widget_Boss_Field
{
	/**
	 * This function returns the form required
	 */
	function form($instance)
	{
		?>
		<p>
			<label for="<?=$this->wp_field_id?>"><?=__($this->name, 'TODO: TD')?></label>
			<select id="<?=$this->wp_field_id?>" name="<?=$this->wp_field_name?>" class="widefat">
				<?php foreach($this->options as $option_key => $option_value) : ?>
					<option <?php selected($instance[$this->key], $option_key); ?> value="<?=$option_key?>"><?=$option_value?></option>
				<?php endforeach; ?>
			</select>
			<p style="font-style: italic;">
				<?=__($this->description, 'TODO: TD');?>
			</p>
		</p>
	<?php
	}

	/**
	 * Santize function.
	 * This runs before data is saved to the database
	 */
	function sanitize($field_value)
	{
		return $field_value;
	}
}