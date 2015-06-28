class [[CLASS]] extends WP_Widget
{
	static $widget_name;
	static $widget_description;
	static $widget_textdomain;
	static $widget_fields;
	static $output_callback;

	function __construct()
	{
		//Load translations
		load_plugin_textdomain(self::$widget_textdomain, false, basename(dirname(__FILE__)) . '/languages' );

		//Init the widget
		parent::__construct(self::$widget_textdomain, __(self::$widget_name, self::$widget_textdomain), array( 'description' => __(self::$widget_description, self::$widget_textdomain), 'classname' => self::$widget_textdomain));
	}

	/**
	* Widget frontend
	*
	* @param array $args
	* @param array $instance
	*/
	public function widget($args, $instance)
	{
		$title = apply_filters('widget_title', (isset($instance['title']) ? $instance['title'] : ''));

		/* Before and after widget arguments are usually modified by themes */
		echo $args['before_widget'];

		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];

		/* Widget output here */
		$this->widget_output($args, $instance);

		/* After widget */
		echo $args['after_widget'];
	}

	/**
	* This function will execute the widget frontend logic.
	* Everything you want in the widget should be output here.
	*/
	private function widget_output($args, $instance)
	{
		//The language construct gods are not with us on this one. This step is required.
		$widget_output_function = self::$output_callback;
		$widget_output_function($args, $instance);
	}

	/**
	* Widget backend
	*
	* @param array $instance
	* @return string|void
	*/
	public function form($instance)
	{
		if(sizeof(self::$widget_fields) === 0)
			echo '<p>' . __('There are no options for this widget.') . '</p>';
		else
		{
			foreach(self::$widget_fields as $field)
			{
				//We have to set some Widget-specific fields (which are only available in this class) before outputting everything
				$field->setWpId($this->get_field_id($field->key));
				$field->setWpName($this->get_field_name($field->key));
				$field->form($instance);
			}
		}
	}

	/**
	* Updating widget by replacing the old instance with new
	*
	* @param array $new_instance
	* @param array $old_instance
	* @return array
	*/
	public function update($new_instance, $old_instance)
	{
		/* Check if we need to run any sanitization functions */
		foreach($new_instance as $key => $field)
		{
			/* Run sanitization function on the field class */
			if(isset(self::$widget_fields[$key]))
				$new_instance[$key] = self::$widget_fields[$key]->sanitize($new_instance[$key]);
		}
		return $new_instance;
	}
}