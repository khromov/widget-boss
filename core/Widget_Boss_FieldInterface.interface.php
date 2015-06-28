<?php
/**
 * This interface handles required methods for fields
 *
 * Interface Widget_Boss_field
 */
interface Widget_Boss_FieldInterface
{
	/**
	 * @param $field_name
	 * @param $field_key
	 * @param $field_description
	 * @param $field_default_value
	 * @param $field_options
	 * @param $textdomain
	 */
	function __construct($field_name, $field_key, $field_description, $field_default_value, $field_options, $textdomain);

	/**
	 * This function echoes out the form
	 *
	 * @param $instance
	 * @return mixed
	 */
	function form($instance);

	/**
	 * This function receives the field data before it is sent to be updated.
	 *
	 * @param $form_data
	 * @return mixed
	 */
	function sanitize($form_data);
}