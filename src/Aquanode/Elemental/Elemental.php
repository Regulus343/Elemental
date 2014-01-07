<?php namespace Aquanode\Elemental;

/*----------------------------------------------------------------------------------------------------------
	Elemental
		An HTML element building composer package that simplifies creation of
		active, selected, or hidden elements.

		created by Cody Jassman / Aquanode - http://aquanode.com
		last updated on January 5, 2014
----------------------------------------------------------------------------------------------------------*/

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Regulus\TetraText\TetraText as Format;

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
	 * @param  mixed    $data
	 * @param  boolean  $bodyOnly
	 * @return void
	 */
	public static function table($config, $data = array(), $bodyOnly = false)
	{
		if (!isset($config['table']))   $config['table']   = array();
		if (!isset($config['columns'])) $config['columns'] = array();
		if (!isset($config['rows']))    $config['rows']    = array();

		$table   = $config['table'];
		$columns = $config['columns'];
		$rows    = $config['rows'];

		$footer  = false;
		if (isset($config['footer']) && $config['footer']) $footer = true;

		for ($c = 0; $c < count($columns); $c++) {
			//set column label from attribute if label is not set
			if (!isset($columns[$c]['label'])) {
				$label = "";
				if (isset($columns[$c]['attribute'])) $label = $columns[$c]['attribute'];

				if ($label == "id") $label = strtoupper($label);
				$label = ucwords(str_replace('_', ' ', $label));
			} else {
				$label = $columns[$c]['label'];
			}

			//if label does not contain HTML, covert special characters
			if ($label == strip_tags($label))
				$columns[$c]['label'] = static::entities($label);

			//format data with "type"
			if (!isset($columns[$c]['type'])) $columns[$c]['type'] = "";

			//format "method" if necessary
			if (isset($columns[$c]['method']))
				$columns[$c]['method'] = str_replace('()', '', $columns[$c]['method']);

			//make header cell class and body cell class blank if they are not set
			if (!isset($columns[$c]['class']))       $columns[$c]['class'] = "";
			if (!isset($columns[$c]['headerClass'])) $columns[$c]['headerClass'] = $columns[$c]['class'];

			//if the first column is the "id" attribute, automatically add a class
			if (isset($columns[$c]['attribute']) && $columns[$c]['attribute'] == "id" && $c == 0) {
				$idClass = "id-attribute";
				$columns[$c]['class'] = ($columns[$c]['class'] != "") ? $columns[$c]['class'].' '.$idClass : $idClass;
			}

			//add header data-sort-field attribute if "sort" is set
			if (isset($columns[$c]['sort']) && (($columns[$c]['sort'] && isset($columns[$c]['attribute'])) || $columns[$c]['sort'] != "")) {
				if (is_bool($columns[$c]['sort']) && $columns[$c]['sort'] && isset($columns[$c]['attribute']))
					$columns[$c]['sortAttribute'] = ' data-sort-field="'.$columns[$c]['attribute'].'"';
				else
					$columns[$c]['sortAttribute'] = ' data-sort-field="'.$columns[$c]['sort'].'"';
			} else {
				$columns[$c]['sortAttribute'] = "";
			}

			//set developer flag
			if (!isset($columns[$c]['developer']))
				$columns[$c]['developer'] = false;

			//check if footer is set through columns array
			if (isset($column['footer'])) $footer = true;
		}

		//set ID prefix for table row IDs
		if (!isset($rows['idPrefix'])) $rows['idPrefix'] = "item";
		$rows['idPrefix'] .= '-';

		if ($bodyOnly)
			$view = "elemental::partials.table_body";
		else
			$view = "elemental::table";

		return View::make($view)
			->with('table', $table)
			->with('columns', $columns)
			->with('rows', $rows)
			->with('footer', $footer)
			->with('data', $data)
			->render();
	}

	/**
	 * Get a class for a table row based on settings.
	 *
	 * @param  object   $row
	 * @param  array    $rowSettings
	 * @return string
	 */
	public static function getTableRowClass($row, $rowSettings = array())
	{
		$class = '';
		if (isset($rowSettings['classModifiers'])) {
			foreach ($rowSettings['classModifiers'] as $potentialClass => $values) {
				$valid = static::testAttributeConditions($row, $values);
				if ($valid) {
					if ($class == '') $class = ' class="';
					$class .= $potentialClass;
				}
			}
			if ($class != '') $class .= '"';
		}
		return $class;
	}

	/**
	 * Get a class for a table column based on settings.
	 *
	 * @param  array    $columnSettings
	 * @return string
	 */
	public static function getTableColumnClass($columnSettings)
	{
		return static::dynamicArea((isset($columnSettings['class']) && $columnSettings['class'] != ""), $columnSettings['class']);
	}

	/**
	 * Format a table cell for table() method.
	 *
	 * @param  mixed    $cellData
	 * @param  string   $type
	 * @return mixed
	 */
	public static function formatTableCellData($cellData, $type, $typeDetails = false)
	{
		if ($type != "") {
			switch (strtolower($type)) {
				case "date":     $cellData = Format::date($cellData, Config::get('elemental::dateFormat')); break;
				case "datetime": $cellData = Format::date($cellData, Config::get('elemental::dateTimeFormat')); break;
				case "money":    $cellData = Format::money($cellData); break;
				case "phone":    $cellData = Format::phone($cellData); break;
				case "boolean":
					if (!$typeDetails)
						$typeDetails = "Yes/No";

					if (!is_array($typeDetails))
						$typeDetails = explode('/', $typeDetails);

					if ((bool) $cellData)
						$cellData = '<span class="boolean-true">'.$typeDetails[0].'</span>';
					else
						$cellData = '<span class="boolean-false">'.$typeDetails[1].'</span>';

					break;
			}
		}
		return $cellData;
	}

	/**
	 * Format a table cell for table() method.
	 *
	 * @param  array    $cellData
	 * @param  string   $type
	 * @return string
	 */
	public static function createElement($element, $item)
	{
		$html = '';

		if (isset($element['conditions']) && is_array($element['conditions'])) {
			$valid = static::testAttributeConditions($item, $element['conditions']);

			if (!$valid) return $html;
		}

		$class = isset($element['class']) ? $element['class'] : '';
		if (isset($element['classModifiers'])) {
			foreach ($element['classModifiers'] as $potentialClass => $values) {
				$valid = static::testAttributeConditions($item, $values);
				if ($valid) {
					if ($class != '') $class .= ' ';
					$class .= $potentialClass;
				}
			}

			if ($class != '')
				$element['class'] = $class;
		}

		if (!isset($element['tag']) || $element['tag'] == "")
			$element['tag'] = "a";

		if (!isset($element['attributes']))
			$element['attributes'] = array();

		if (isset($element['class']))
			$element['attributes']['class'] = $element['class'];

		if (isset($element['selfClosing']) && $element['selfClosing']) {
			$selfClosing = true;
		} else {
			$selfClosing = false;
		}

		if (isset($element['url']) && !isset($element['href']))
			$element['href'] = $element['url'];

		if ($element['tag'] == "a") {
			$element['attributes']['href'] = "";
			if (isset($element['uri'])) {
				$element['attributes']['href'] = URL::to($element['uri']);
			} else if (isset($element['href']) && $element['href'] != "") {
				$element['attributes']['href'] = $element['href'];
			}
		}

		//add data to attributes where necessary
		foreach ($element['attributes'] as $attribute => $value) {
			if (preg_match("/\:([a-zA-Z\_].*)/", $value, $match)) {
				$segments    = explode('/', $match[1]);
				$segments[0] = isset($item[$segments[0]]) ? $item[$segments[0]] : '';

				$element['attributes'][$attribute] = str_replace($match[0], implode('/', $segments), $element['attributes'][$attribute]);
			}
		}

		$html .= '<'.$element['tag'].static::attributes($element['attributes']);
		if (!$selfClosing) $html .= '>';

		if (isset($element['icon']) && $element['icon'] != "")
			$html .= '<span class="glyphicon glyphicon-'.trim($element['icon']).'"></span>';

		if (isset($element['text']) && $element['text'] != "") {
			if (preg_match("/\:([a-zA-Z\_].*)/", $value, $match)) {
				$segments    = explode('/', $match[1]);
				$segments[0] = isset($item[$segments[0]]) ? $item[$segments[0]] : '';

				$element['text'] = str_replace($match[0], implode('/', $segments), $element['text']);
			}
			$html .= $element['text'];
		}

		if ($selfClosing) {
			$html .= ' />';
		} else {
			$html .= '</'.$element['tag'].'>';
		}

		return $html;
	}

	/**
	 * Test attribute conditions to decide whether to show elements in the table() method.
	 *
	 * @param  mixed   $item
	 * @param  array   $values
	 * @return boolean
	 */
	public static function testAttributeConditions($item, $values)
	{
		if (!is_array($item))
			$item = $item->toArray();

		$valid = true;
		foreach ($values as $attribute => $value) {
			$operator  = "==";
			$operators = array(
				'==',
				'!=',
				'<',
				'<=',
				'>',
				'>=',
			);
			foreach ($operators as $operatorListed) {
				if (substr($value, 0, strlen($operatorListed)) == $operatorListed) {
					$value = trim(substr($value, strlen($operatorListed)));
					$operator = $operatorListed;
				}
			}

			if ($value == "true")
				$value = true;

			if ($value == "false")
				$value = true;

			$attributeValue = $item[$attribute];
			if (is_bool($value)) {
				$attributeValue = (bool) $attributeValue;
			} else if (is_integer($value)) {
				$attributeValue = (int)  $attributeValue;
			}

			if ($operator == "==" && $attributeValue != $value) $valid = false;
			if ($operator == "!=" && $attributeValue == $value) $valid = false;
			if ($operator == "<"  && $attributeValue >= $value) $valid = false;
			if ($operator == "<=" && $attributeValue > $value)  $valid = false;
			if ($operator == ">"  && $attributeValue <= $value) $valid = false;
			if ($operator == ">=" && $attributeValue < $value)  $valid = false;
		}

		return $valid;
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