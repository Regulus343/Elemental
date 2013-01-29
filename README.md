Elemental
=========

**An HTML element building composer package that simplifies creation of active, selected, or hidden elements.**

Elemental is a simple HTML element creation library, primarily for creating dynamic elements (such as "active", "selected", or "hidden" classed elements that depend on a certain variable as their trigger).

Elemental removes the need for you do to things like this in your markup:

	<div id="content"<?php if (isset($contentHidden)) echo ' class="hidden"'; ?>>
		...
	</div><!-- /#content -->

Instead, you will be able to use this simple syntax:

	<?php echo HTML::openHiddenArea('div', '#content', isset($contentHidden)); ?>
		...
	<?php echo HTML::closeArea('div', '#content'); ?>

- [Installation](#installation)
- [Creating Dynamic Elements](#creating-dynamic-elements)

<a name="installation"></a>
## Installation

To install Elemental, make sure "aquanode/elemental" has been added to Laravel 4's config.json file.

	"require": {
		"aquanode/elemental": "dev-master"
	},

Then run `php composer.phar update` from the command line. Composer will install the Elemental package. Now, all you have to do is register the service provider and set up Elemental's alias in `app/config/app.php`. Add this to the `providers` array:

	'Aquanode\Elemental\ElementalServiceProvider',

And add this to the `aliases` array:

	'HTML' => 'Aquanode\Elemental\Elemental',

You may use 'Elemental', or another alias, but 'HTML' is recommended for the sake of simplicity. Elemental is now ready to go.

<a name="creating-dynamic-elements"></a>
## Creating Dynamic Elements

**Creating a dynamic element that may have a "hidden" class:**

	echo HTML::openHiddenArea('div', array('id' => 'side-content', 'class' => 'content', isset($sideContentHidden));

The second argument can contain a string for a single class or ID like ".class" or "#id" or it may contain an array of all the attributes you would like to use.

You may use `HTML::openActiveArea()` and `HTML::openSelectedArea()` which add "active" and "selected" classes respectively. All of these classes make use of the base method `HTML::openDynamicArea()` which includes the class name as its fourth argument. The three methods that make use of it simply exist as a short form to create some common dynamic elements.

**Closing an element:**

	echo HTML::closeArea('div');

This method exists for the sake of completeness and also to prevent erroneous syntax highlighting in certain IDEs. If you open an HTML element in PHP, you should create your closing tag with PHP as well. You may include the class (".class") or ID ("#id") of the element as the second argument to have an HTML comment added to the line for the closing tag. This may help when trying to find where elements are opened and where they are closed in your source code.

**Closing an element and adding an identifying comment:**

	echo HTML::closeArea('div', '#content');

The above code outputs this:

	</div><!-- /#content -->