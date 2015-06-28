<?php
class Widget_Boss_Textfield extends Widget_Boss_Field
{
	/**
	 * This function returns the form required
	 */
	function form($instance)
	{
		?>
		<p>
			<label for="<?=$this->wp_field_id?>"><?=__($this->name, 'TODO: TD')?></label>
			<input class="widefat" id="<?=$this->wp_field_id?>" name="<?=$this->wp_field_name?>" type="text" value="<?=esc_attr(isset($instance[$this->key]) ? $instance[$this->key] : $this->default_value)?>" />
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