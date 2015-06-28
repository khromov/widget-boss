<?php
/*
Plugin Name: Widget Boss
Plugin URI:
Description: Effortlessly create WordPress widgets with the Widgets Boss framework.
Version: 0.9
Author: khromov
Author URI: http://snippets.khromov.se
License: GPL2
*/

include 'core/Widget_Boss_FieldInterface.interface.php';
include 'core/Widget_Boss_Field.class.php';
include 'core/Widget_Boss.class.php';

include 'functions.php';

$widget_boss = new Widget_Boss();