<?php
/**
 * Abstract model of a field
 *
 * Class Widget_Boss_Field
 */
abstract class Widget_Boss_Field implements Widget_Boss_FieldInterface
{
	/**
	 * @var $name string	Widget name
	 * @var $key string		Unique widget key
	 * @var $description string	Description shown to user
	 * @var $default_value string	The fields default value
	 * @var $extra string  Any extra data can be passed to the field using this key
	 * @var $textdomain string	The textdomain for the field
	 */
	var $name;
	var $key;
	var $description;
	var $default_value;
	var $options;
	var $textdomain;

	/**
	 * @var $wp_field_id string 	WordPress unique field ID (per widget instance, resolved at runtime)
	 * @var $wp_field_name string	WordPress unique field name (per widget instance, resolved at runtime)
	 */
	var $wp_field_id;
	var $wp_field_name;

	function __construct($field_name, $field_key, $field_description, $field_default_value, $field_options, $textdomain)
	{
		$this->name = $field_name;
		$this->key = $field_key;
		$this->description = $field_description;
		$this->default_value = $field_default_value;
		$this->options = $field_options;
		$this->textdomain = $textdomain;
	}

	/**
	 * Required to set ID from the widget class
	 */
	function setWpId($field_id)
	{
		$this->wp_field_id = $field_id;
	}

	/**
	 * Required to set field name from the widget class
	 */
	function setWpName($field_name)
	{
		$this->wp_field_name = $field_name;
	}

	/**
	 * Sanitize function.
	 * This is a dummy and can be replaced for your own function in the field handler.
	 */
	function sanitize($field_value)
	{
		return $instance;
	}
}