<?php namespace Aquanode\Elemental;

/*----------------------------------------------------------------------------------------------------------
	Elemental
		An HTML element building composer package that simplifies creation of
		active, selected, or hidden elements.

		created by Cody Jassman / Aquanode - http://aquanode.com
		last updated on September 10, 2013
----------------------------------------------------------------------------------------------------------*/

use Illuminate\Support\Facades\View;

class Elemental {

	/**
	 * Create an opening tag for an element that has a toggle for being selected. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $active
	 * @return void
	 */
	public static function openActiveArea($element = 'div', $attributes = array(), $active = false)
	{
		return static::openDynamicArea($element, $attributes, $active, 'active');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being selected. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $selected
	 * @return void
	 */
	public static function openSelectedArea($element = 'div', $attributes = array(), $selected = false)
	{
		return static::openDynamicArea($element, $attributes, $selected, 'selected');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being hidden. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $hidden
	 * @return void
	 */
	public static function openHiddenArea($element = 'div', $attributes = array(), $hidden = false)
	{
		return static::openDynamicArea($element, $attributes, $hidden, 'hidden');
	}

	/**
	 * Create an opening tag for an element that can easily toggle a class. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $active
	 * @param  string   $class
	 * @return void
	 */
	public static function openDynamicArea($element = 'div', $attributes = array(), $active = false, $class = 'selected')
	{
		$attributesFormatted = $attributes;
		if (is_string($attributes)) {
			if (substr($attributes, 0, 1) == ".") {
				$attributesFormatted = array('class' => substr($attributes, 1));
			} else if (substr($attributes, 0, 1) == "#") {
				$attributesFormatted = array('id' => substr($attributes, 1));
			}
		}
		if (!is_array($attributesFormatted)) $attributesFormatted = array();

		if ($active) {
			if (isset($attributesFormatted['class']) && $attributesFormatted['class'] != "") {
				$attributesFormatted['class'] .= ' '.$class;
			} else {
				$attributesFormatted['class'] = $class;
			}
		}

		return '<'.$element.static::attributes($attributesFormatted).'>' . "\n";
	}

	/**
	 * Add a class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $active
	 * @param  string   $class
	 * @param  boolean  $inClass
	 * @return void
	 */
	public static function dynamicArea($active = false, $class = 'selected', $inClass = false)
	{
		if ($active) {
			if ($inClass) {
				return ' '.$class;
			} else {
				return ' class="'.$class.'"';
			}
		}
		return '';
	}

	/**
	 * Add an "active" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $active
	 * @param  boolean  $inClass
	 * @return void
	 */
	public static function activeArea($active = false, $inClass = false)
	{
		return static::dynamicArea($active, 'active', $inClass);
	}

	/**
	 * Add an "selected" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $selected
	 * @param  boolean  $inClass
	 * @return void
	 */
	public static function selectedArea($selected = false, $inClass = false)
	{
		return static::dynamicArea($selected, 'selected', $inClass);
	}

	/**
	 * Add an "hidden" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $hidden
	 * @param  boolean  $inClass
	 * @return void
	 */
	public static function hiddenArea($hidden = false, $inClass = false)
	{
		return static::dynamicArea($hidden, 'hidden', $inClass);
	}

	/**
	 * Add a class to an element based on an array of options. The indexes of the array are the strng options and the
	 * values are the corresponding classes to add to the element.
	 *
	 * @param  boolean  $value
	 * @param  string   $options
	 * @param  boolean  $inClass
	 * @return void
	 */
	public static function dynamicAreaOptions($value, $options = array(), $inClass = false)
	{
		if (isset($options[$value])) {
			if ($inClass) {
				return ' '.$options[$value];
			} else {
				return ' class="'.$options[$value].'"';
			}
		}
		return '';
	}

	/**
	 * Create a close tag element that has a toggle for being hidden. Attributes can be defined as a
	 * string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  string   $identifier
	 * @param  boolean  $active
	 * @return void
	 */
	public static function closeArea($element = 'div', $identifier = null)
	{
		$html = '</'.$element.'>';
		if ($identifier && is_string($identifier) && $identifier != "") {
			$html .= '<!-- /'.$identifier.' -->' . "\n";
		}
		return $html;
	}

	/**
	 * Create a table according to a complex configuration array.
	 *
	 * @param  array    $config
	 * @param  array    $data
	 * @return void
	 */
	public static function table($config, $data = array())
	{
		if (!isset($config['table']))   $config['table']   = array();
		if (!isset($config['columns'])) $config['columns'] = array();
		if (!isset($config['rows']))    $config['rows']    = array();

		$table   = $config['table'];
		$columns = $config['columns'];
		$rows    = $config['rows'];

		return View::make('elemental::table')
			->with('table', $table)
			->with('columns', $columns)
			->with('rows', $rows)
			->with('data', $data)
			->render();

		/*$html  = '<table class="table'.(isset($table['class']) && $table['class'] != "" ? ' '.$table['class'] : '').'">';
		$html .= '<thead><tr>';

		if (!isset($table['columns'])) $table['columns'] = array();
		if (!isset($table['rows']))    $table['rows']    = array();

		$footer = false;
		foreach ($setup['columns'] as $column) {
			if (isset($column['title'])) {
				$html .= '<th>'.$column['title'].'</th>';
			} else if (isset($column['attribute'])) {
				$title = $column['attribute'];
				if ($title == "id") $title = strtoupper($title);
				$title = ucwords(str_replace(' ', '_', $column['attribute']));
				$html .= '<th>'.$title.'</th>';
			} else {
				$html .= '<th>&nbsp;</th>';
			}

			if (isset($column['footer'])) $footer = true;
		}

		$html .= '</tr></thead><tbody>';

		$html .= '</tbody>';

		if ($footer) {
			$html .= '<tfoot>';

			$html .= '</tfoot>';
		}

		$html .= '</table>';
		return $html;*/
	}

	/**
	 * Build a list of HTML attributes from an array.
	 *
	 * @param  array   $attributes
	 * @return string
	 */
	public static function attributes($attributes)
	{
		$html = array();

		foreach ((array) $attributes as $key => $value)
		{
			// For numeric keys, we will assume that the key and the value are the
			// same, as this will convert HTML attributes such as "required" that
			// may be specified as required="required", etc.
			if (is_numeric($key)) $key = $value;

			if ( ! is_null($value))
			{
				$html[] = $key.'="'.static::entities($value).'"';
			}
		}

		return (count($html) > 0) ? ' '.implode(' ', $html) : '';
	}

	/**
	 * Convert HTML characters to entities.
	 *
	 * The encoding specified in the application configuration file will be used.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function entities($value)
	{
		return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}

}