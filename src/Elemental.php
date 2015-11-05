<?php namespace Regulus\Elemental;

/*----------------------------------------------------------------------------------------------------------
	Elemental
		An HTML element building composer package for Laravel 5 that simplifies
		creation of active, selected, or hidden elements.

		created by Cody Jassman
		version 0.5.2
		last updated on November 4, 2015
----------------------------------------------------------------------------------------------------------*/

use Illuminate\Html\HtmlBuilder;

use Illuminate\Routing\UrlGenerator;

use Illuminate\Support\Facades\View;

use Regulus\TetraText\Facade as Format;

class Elemental extends HtmlBuilder {

	/**
	 * The URL generator instance.
	 *
	 * @var \Illuminate\Routing\UrlGenerator
	 */
	protected $url;

	/**
	 * Create a new HTML builder instance.
	 *
	 * @param  \Illuminate\Routing\UrlGenerator  $url
	 * @return void
	 */
	public function __construct(UrlGenerator $url = null)
	{
		$this->url = $url;
	}

	/**
	 * Create an opening tag for an element that has a toggle for being selected. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $active
	 * @return string
	 */
	public function openActiveArea($element = 'div', $attributes = [], $active = false)
	{
		return $this->openDynamicArea($element, $attributes, $active, 'active');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being selected. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $selected
	 * @return string
	 */
	public function openSelectedArea($element = 'div', $attributes = [], $selected = false)
	{
		return $this->openDynamicArea($element, $attributes, $selected, 'selected');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being hidden. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $hidden
	 * @return string
	 */
	public function openHiddenArea($element = 'div', $attributes = [], $hidden = false)
	{
		return $this->openDynamicArea($element, $attributes, $hidden, 'hidden');
	}

	/**
	 * Create an opening tag for an element that has a toggle for being invisible. Attributes can
	 * be defined as a string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  mixed    $attributes
	 * @param  boolean  $invisible
	 * @return string
	 */
	public function openInvisibleArea($element = 'div', $attributes = [], $invisible = false)
	{
		return $this->openDynamicArea($element, $attributes, $invisible, 'invisible');
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
	 * @return string
	 */
	public function openDynamicArea($element = 'div', $attributes = [], $active = false, $class = 'selected')
	{
		$attributesFormatted = $attributes;

		if (is_string($attributes))
		{
			if (substr($attributes, 0, 1) == ".")
				$attributesFormatted = ['class' => substr($attributes, 1)];
			else if (substr($attributes, 0, 1) == "#")
				$attributesFormatted = ['id' => substr($attributes, 1)];
		}

		if (!is_array($attributesFormatted))
			$attributesFormatted = [];

		if ($active)
		{
			if (isset($attributesFormatted['class']) && $attributesFormatted['class'] != "")
				$attributesFormatted['class'] .= ' '.$class;
			else
				$attributesFormatted['class'] = $class;
		}

		return '<'.$element.$this->attributes($attributesFormatted).'>' . "\n";
	}

	/**
	 * Add a class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $active
	 * @param  mixed    $class
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function dynamicArea($active = false, $class = 'selected', $inClass = false)
	{
		$alternateClass = null;

		if (is_array($class) && count($class) == 2)
		{
			$alternateClass = $class[1];

			$class = $class[0];
		}

		$usedClass = null;

		if ($active)
		{
			$usedClass = $class;
		}
		else
		{
			if (!is_null($alternateClass))
				$usedClass = $alternateClass;
		}

		if (!is_null($usedClass))
		{
			if ($inClass)
				return ' '.$usedClass;
			else
				return ' class="'.$usedClass.'"';
		}

		return "";
	}

	/**
	 * Add an "active" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $active
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function activeArea($active = false, $inClass = false)
	{
		return $this->dynamicArea($active, 'active', $inClass);
	}

	/**
	 * Add an "selected" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $selected
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function selectedArea($selected = false, $inClass = false)
	{
		return $this->dynamicArea($selected, 'selected', $inClass);
	}

	/**
	 * Add an "hidden" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $hidden
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function hiddenArea($hidden = false, $inClass = false)
	{
		return $this->dynamicArea($hidden, 'hidden', $inClass);
	}

	/**
	 * Add an "invisible" class to an element based on whether the given variable is true.
	 *
	 * @param  boolean  $invisible
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function invisibleArea($invisible = false, $inClass = false)
	{
		return $this->dynamicArea($invisible, 'invisible', $inClass);
	}

	/**
	 * Add a class to an element based on an array of options. The indexes of the array are the strng options and the
	 * values are the corresponding classes to add to the element.
	 *
	 * @param  boolean  $value
	 * @param  string   $options
	 * @param  boolean  $inClass
	 * @return string
	 */
	public function dynamicAreaOptions($value, $options = [], $inClass = false)
	{
		if (isset($options[$value]))
		{
			if ($inClass)
				return ' '.$options[$value];
			else
				return ' class="'.$options[$value].'"';
		}

		return "";
	}

	/**
	 * Create a close tag element that has a toggle for being hidden. Attributes can be defined as a
	 * string like ".class" or "#id" to simply specify a class or ID, or as an associative
	 * array of attributes.
	 *
	 * @param  string   $element
	 * @param  string   $identifier
	 * @param  boolean  $active
	 * @return string
	 */
	public function closeArea($element = 'div', $identifier = null)
	{
		$html = '</'.$element.'>';

		if ($identifier && is_string($identifier) && $identifier != "")
			$html .= '<!-- /'.$identifier.' -->' . "\n";

		return $html;
	}

	/**
	 * Create a table according to a complex configuration array.
	 *
	 * @param  array    $config
	 * @param  mixed    $data
	 * @param  boolean  $bodyOnly
	 * @return string
	 */
	public function table($config, $data = [], $bodyOnly = false)
	{
		if (!isset($config['table']))   $config['table']   = [];
		if (!isset($config['columns'])) $config['columns'] = [];
		if (!isset($config['rows']))    $config['rows']    = [];

		// allow snakecase for variables in case table config is passed from a snakecased config file
		if (!isset($config['table']['noDataMessage']) && isset($config['table']['no_data_message']))
		{
			$config['table']['noDataMessage'] = $config['table']['no_data_message'];
			unset($config['table']['no_data_message']);
		}

		if (!isset($config['rows']['idPrefix']) && isset($config['rows']['id_prefix']))
		{
			$config['rows']['idPrefix'] = $config['rows']['id_prefix'];
			unset($config['rows']['id_prefix']);
		}

		if (!isset($config['rows']['classModifiers']) && isset($config['rows']['class_modifiers']))
		{
			$config['rows']['classModifiers'] = $config['rows']['class_modifiers'];
			unset($config['rows']['class_modifiers']);
		}

		$table   = $config['table'];
		$columns = $config['columns'];
		$rows    = $config['rows'];

		//turn the footer on/off
		$footer  = false;
		if (isset($config['footer']) && $config['footer'])
			$footer = true;

		for ($c = 0; $c < count($columns); $c++)
		{
			// set column label from attribute if label is not set
			if (!isset($columns[$c]['label']))
			{
				$label = "";

				if (isset($columns[$c]['attribute']))
					$label = $columns[$c]['attribute'];

				if ($label == "id")
					$label = strtoupper($label);

				$label = ucwords(str_replace('_', ' ', $label));
			} else {
				$label = $columns[$c]['label'];
			}

			// if label does not contain HTML, covert special characters
			if ($label == strip_tags($label))
				$columns[$c]['label'] = $this->entities($label);

			// format data with "type"
			if (!isset($columns[$c]['type']))
				$columns[$c]['type'] = "";

			// make header cell class and body cell class blank if they are not set
			if (!isset($columns[$c]['class']))
				$columns[$c]['class'] = "";

			if (!isset($columns[$c]['headerClass']))
				$columns[$c]['headerClass'] = isset($columns[$c]['header_class']) ? $columns[$c]['header_class'] : $columns[$c]['class'];

			// set body cell class if it is specifically set
			if (!isset($columns[$c]['bodyClass']) && isset($columns[$c]['body_class']))
			{
				$columns[$c]['bodyClass'] = $columns[$c]['body_class'];
				unset($columns[$c]['body_class']);
			}

			if (isset($columns[$c]['bodyClass']) && $columns[$c]['bodyClass'] != "")
				$columns[$c]['class'] = $columns[$c]['bodyClass'];

			// if the first column is the "id" attribute, automatically add a class
			if (isset($columns[$c]['attribute']) && $columns[$c]['attribute'] == "id" && $c == 0)
			{
				$idClass = "id-attribute";
				$columns[$c]['class'] = ($columns[$c]['class'] != "") ? $columns[$c]['class'].' '.$idClass : $idClass;
			}

			// add header data-sort-field attribute if "sort" is set
			if (isset($columns[$c]['sort']) && (($columns[$c]['sort'] && isset($columns[$c]['attribute'])) || $columns[$c]['sort'] != ""))
			{
				if (is_bool($columns[$c]['sort']) && $columns[$c]['sort'] && isset($columns[$c]['attribute']))
					$columns[$c]['sortAttribute'] = ' data-sort-field="'.$columns[$c]['attribute'].'"';
				else
					$columns[$c]['sortAttribute'] = ' data-sort-field="'.$columns[$c]['sort'].'"';
			} else {
				$columns[$c]['sortAttribute'] = "";
			}

			// camelcase element attributes if any snakecased ones exist
			if (isset($columns[$c]['elements']) && is_array($columns[$c]['elements']))
			{
				for ($e = 0; $e < count($columns[$c]['elements']); $e++)
				{
					if (isset($columns[$c]['elements'][$e]['class_modifiers']))
					{
						$columns[$c]['elements'][$e]['classModifiers'] = $columns[$c]['elements'][$e]['class_modifiers'];
						unset($columns[$c]['elements'][$e]['class_modifiers']);
					}

					if (isset($columns[$c]['elements'][$e]['self_closing']))
					{
						$columns[$c]['elements'][$e]['selfClosing'] = $columns[$c]['elements'][$e]['self_closing'];
						unset($columns[$c]['elements'][$e]['self_closing']);
					}
				}
			}

			// set developer flag
			if (!isset($columns[$c]['developer']))
				$columns[$c]['developer'] = false;

			// check if footer is set through columns array
			if (isset($column['footer']))
				$footer = true;
		}

		// set ID prefix for table row IDs
		if (!isset($rows['idPrefix']))
			$rows['idPrefix'] = "item";

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
	public function getTableRowClass($row, $rowSettings = [])
	{
		$class = '';

		if (isset($rowSettings['classModifiers']))
		{
			foreach ($rowSettings['classModifiers'] as $potentialClass => $values)
			{
				$valid = $this->testAttributeConditions($row, $values);

				if ($valid)
				{
					if ($class != "")
						$class .= " ";

					$class .= $potentialClass;
				}
			}
		}

		return $class != "" ? ' class="'.$class.'"' : '';
	}

	/**
	 * Get a class for a table column based on settings.
	 *
	 * @param  array    $columnSettings
	 * @return string
	 */
	public function getTableColumnClass($columnSettings)
	{
		return $this->dynamicArea((isset($columnSettings['class']) && $columnSettings['class'] != ""), $columnSettings['class']);
	}

	/**
	 * Format a table cell for table() method.
	 *
	 * @param  mixed    $cellData
	 * @param  string   $type
	 * @return mixed
	 */
	public function formatTableCellData($cellData, $type, $typeDetails = false)
	{
		if ($type != "")
		{
			switch (strtolower($type))
			{
				case "date":      $cellData = Format::date($cellData);     break;
				case "datetime":  $cellData = Format::dateTime($cellData); break;
				case "timestamp": $cellData = Format::dateTime($cellData); break;
				case "money":     $cellData = Format::money($cellData);    break;
				case "phone":     $cellData = Format::phone($cellData);    break;
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
	 * Get the result of a method for an object.
	 *
	 * @param  object   $object
	 * @param  string   $method
	 * @return mixed
	 */
	public function getMethodResult($object, $method)
	{
		$method = Format::getMethodFromString($method);
		if (is_null($method))
			return $method;

		return call_user_func_array([$object, $method['name']], $method['parameters']);
	}

	/**
	 * Format a table cell for table() method.
	 *
	 * @param  array    $cellData
	 * @param  string   $type
	 * @return string
	 */
	public function createElement($element, $item)
	{
		$html = '';

		if (isset($element['ignore']) && $element['ignore'])
			return $html;

		if (isset($element['conditions']) && is_array($element['conditions']))
		{
			$valid = $this->testAttributeConditions($item, $element['conditions']);

			if (!$valid)
				return $html;
		}

		$class = isset($element['class']) ? $element['class'] : '';

		if (isset($element['classModifiers']))
		{
			foreach ($element['classModifiers'] as $potentialClass => $values)
			{
				$valid = $this->testAttributeConditions($item, $values);

				if ($valid)
				{
					if ($class != '')
						$class .= ' ';

					$class .= $potentialClass;
				}
			}

			if ($class != '')
				$element['class'] = $class;
		}

		if (!isset($element['tag']) || $element['tag'] == "")
			$element['tag'] = "a";

		if (!isset($element['attributes']))
			$element['attributes'] = [];

		if (isset($element['class']))
			$element['attributes']['class'] = $element['class'];

		if (isset($element['selfClosing']) && $element['selfClosing'])
			$selfClosing = true;
		else
			$selfClosing = false;

		if (isset($element['url']) && !isset($element['href']))
			$element['href'] = $element['url'];

		if ($element['tag'] == "a")
		{
			$element['attributes']['href'] = "";

			if (isset($element['uri']))
				$element['attributes']['href'] = url($element['uri']);
			else if (isset($element['href']) && $element['href'] != "")
				$element['attributes']['href'] = $element['href'];
		}

		if (isset($element['attributes']) && isset($element['attributes']['href']) && class_exists('Regulus\Identify\Facade'))
		{
			$accessVerb = "get";

			if (isset($element['attributes']['data-action-type']))
				$accessVerb = $element['attributes']['data-action-type'];

			$accessible = \Regulus\Identify\Facade::hasAccess($element['attributes']['href'], $accessVerb);

			if (!$accessible)
				return $html;
		}

		// add data to attributes where necessary
		foreach ($element['attributes'] as $attribute => $value)
		{
			if (preg_match("/\:([a-zA-Z\_].*)/", $value, $match))
			{
				$segments    = explode('/', $match[1]);
				$segments[0] = isset($item[$segments[0]]) ? $item[$segments[0]] : '';

				$element['attributes'][$attribute] = str_replace($match[0], implode('/', $segments), $element['attributes'][$attribute]);
			}
		}

		$html .= '<'.$element['tag'].$this->attributes($element['attributes']);

		if (!$selfClosing)
			$html .= '>';

		if (isset($element['icon']) && $element['icon'] != "")
		{
			$iconElement     = config('html.icon.element');
			$iconClassPrefix = config('html.icon.class_prefix');

			$html .= '<'.$iconElement.' class="'.$iconClassPrefix.trim($element['icon']).'"></'.$iconElement.'>';
		}

		if (isset($element['text']) && $element['text'] != "")
		{
			if (preg_match("/\:([a-zA-Z\_].*)/", $value, $match))
			{
				$segments    = explode('/', $match[1]);
				$segments[0] = isset($item[$segments[0]]) ? $item[$segments[0]] : '';

				$element['text'] = str_replace($match[0], implode('/', $segments), $element['text']);
			}

			$html .= $element['text'];
		}

		if ($selfClosing)
			$html .= ' />';
		else
			$html .= '</'.$element['tag'].'>';

		return $html;
	}

	/**
	 * Test attribute conditions to decide whether to show elements in the table() method.
	 *
	 * @param  mixed   $item
	 * @param  array   $values
	 * @return boolean
	 */
	public function testAttributeConditions($item, $values)
	{
		$valid = true;

		foreach ($values as $attribute => $value)
		{
			$operator = "==";

			$operators = [
				'==',
				'!=',
				'<',
				'<=',
				'>',
				'>=',
			];

			foreach ($operators as $operatorListed)
			{
				if (substr($value, 0, strlen($operatorListed)) == $operatorListed)
				{
					$value    = trim(substr($value, strlen($operatorListed)));
					$operator = $operatorListed;
				}
			}

			if ($value == "true")
				$value = true;

			if ($value == "false")
				$value = true;

			// method was used instead of attribute; set value by calling method
			if (substr($attribute, -2) == "()")
			{
				$method         = substr($attribute, 0, (strlen($attribute) - 2));
				$attributeValue = $item->$method();
			} else {
				$attributeValue = $item->{$attribute};
			}

			if (is_bool($value))
				$attributeValue = (bool) $attributeValue;
			else if (is_integer($value))
				$attributeValue = (int)  $attributeValue;

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
	public function attributes($attributes)
	{
		$html = [];

		foreach ((array) $attributes as $key => $value)
		{
			// for numeric keys, we will assume that the key and the value are the
			// same, as this will convert HTML attributes such as "required" that
			// may be specified as required="required", etc.
			if (is_numeric($key))
				$key = $value;

			if (!is_null($value))
			{
				$html[] = $key.'="'.$this->entities($value).'"';
			}
		}

		return (count($html) > 0) ? ' '.implode(' ', $html) : '';
	}

}