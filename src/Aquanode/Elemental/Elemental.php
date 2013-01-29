<?php namespace Aquanode\Elemental;

/*----------------------------------------------------------------------------------------------------------
	Elemental
		An HTML element building class that simplifies creation of active, selected, or hidden elements.

		created by Cody Jassman / Aquanode - http://aquanode.com
		last updated on January 28, 2013
----------------------------------------------------------------------------------------------------------*/

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
	 * @param  boolean  $active
	 * @return void
	 */
	public static function openSelectedArea($element = 'div', $attributes = array(), $active = false)
	{
		return static::openDynamicArea($element, $attributes, $active, 'selected');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being hidden. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $active
	 * @return void
	 */
	public static function openHiddenArea($element = 'div', $attributes = array(), $active = false)
	{
		return static::openDynamicArea($element, $attributes, $active, 'hidden');
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
				$attributesFormatted['class'] .= $class;
			}
		}

		return '<'.$element.static::attributes($attributesFormatted).'>' . "\n";
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