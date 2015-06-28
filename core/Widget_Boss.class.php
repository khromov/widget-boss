<?php
/**
 * Main class
 * Handles dynamic Widgets and their associated Fields.
 *
 * Class Widget_Boss
 */
class Widget_Boss
{
	var $widgets;

	function __construct()
	{
		/**
		 * Initialize widgets storage
		 */
		$this->widgets = array();

		/**
		 * Initialize field types
		 */
		foreach(glob(__DIR__ . "/../fields/*.php") as $filename)
			include $filename;

		/**
		 * Initialize custom field types
		 */
		add_action('plugins_loaded', function()
		{
			do_action('wb_register_field_types');
		}, 11);

		//PHP 5.3 hack
		$self = $this;

		/**
		 * Create the widget registration action, letting users create widgets from themes
		 */
		add_action('after_setup_theme', function() use ($self)
		{
			$self->widgets = apply_filters('wb_widgets', $self->widgets);
		}, 11);

		/**
		 * Register all widgets with WordPress
		 */
		add_action('widgets_init', function() use ($self)
		{
			$self->register_widgets();
		});
	}

	/**
	 * Handles widget registration
	 */
	function register_widgets()
	{
		//Loop over each widget in storage
		foreach($this->widgets as $widget_key => $widget)
		{
			//Generate the widget class. This is either set by users or a sanitized classname based on [a-zA-Z0-9_] + first letter not a number.
			$widget_class = isset($widget['classname']) ? $widget['classname'] : $this->clean_name($widget['name']);

			//Generates the widget textdomain. We use the same as class because otherwise widgets "disappear" when you change the text domain.
			$widget_textdomain = $widget_class;

			//Replacements for template
			$replacement_array = array('class'	=> $widget_class);

			//Get template code for generating widget class
			$template = file_get_contents(__DIR__ . '/../templates/widget_class.tpl');

			//Process template
			$template = str_replace('[[CLASS]]', $widget_class, $template);

			//Create the widget dynamically. eval() is evil, but this is a legitimate use case, I promise!
			eval($template);

			//Set widget name, description, and textdomain and output callback
			$widget_class::$widget_name = $widget['name'];
			$widget_class::$widget_description = $widget['description'];
			$widget_class::$widget_textdomain = $widget_textdomain;
			$widget_class::$output_callback = $widget['output_callback'];

			//Initialize the fields array
			$this->widgets[$widget_key]['fields_oo'] = array();

			//Parse fields to their object representations
			foreach($widget['fields'] as $field)
			{
				//If field type exists, initialize it
				$widget_class_name = 'Widget_Boss_' . ucfirst($field['type']);
				if(class_exists($widget_class_name))
				{
					//Create fields. You may pass the array key $field['extra'] to send some custom values to the field class
					$this->widgets[$widget_key]['fields_oo'][$field['key']] = new $widget_class_name($field['name'], $field['key'], $field['description'], $field['default_value'], (isset($field['options']) ? $field['options'] : array()), $widget_textdomain);
				}
				else
					echo '<strong>ERROR: </strong>Widget_Boss_' . ucfirst($field['type']) . ' is not a valid field type'; //FIXME: Something better for error reporting plz
			}

			//Add fields to widget
			$widget_class::$widget_fields = $this->widgets[$widget_key]['fields_oo'];

			//Register the widget
			register_widget($widget_class);
		}
	}

	/**
	 * Function for cleaning up strings for use as class names or textdomains
	 *
	 * @param $in string A regular string
	 * @return string A clean name for use as class or textdomain
	 */
	function clean_name($in)
	{
		return str_replace('-', '_', 'wb_' . sanitize_title($in));
	}

	/**
	 * Adds a widget
	 *
	 * @param $config
	 */
	public static function add_widget($config)
	{
		global $widget_boss;
		$widget_boss->widgets[] = $config;
	}

	/**
	 * Removes a widget by class name
	 *
	 * @param $class
	 */
	public static function remove_widget($class)
	{
		global $widget_boss;
		//TODO: Implement
	}

	/**
	 * Checks if we are on a widget page so we can prepare data for reading
	 *
	 * @return bool
	 */
	public static function is_widget_admin()
	{
		//AJAX
		if(is_admin() && defined('DOING_AJAX') && DOING_AJAX && preg_match('/.*\/wp-admin\/(widgets.php).*/', wp_get_referer()) === 1)
		{
			return true;
		} //Initial load or accessibility mode
		else if(preg_match('/.*\/wp-admin\/(widgets.php|customize.php).*/', $_SERVER['REQUEST_URI']) === 1)
		{
			return true;
		}
		else //Something else
			return false;
	}
}