Elemental
=========

**An HTML element building composer package for Laravel 5 that simplifies creation of active, selected, or hidden elements.**

> **Note:** For Laravel 4, you may use <a href="https://github.com/Regulus343/Elemental/tree/v0.3.3">version 0.3.3</a>.

Elemental is a simple HTML element creation library, primarily for creating dynamic elements (such as "active", "selected", or "hidden" classed elements that depend on a certain variable as their trigger).

Elemental removes the need for you do to things like this in your markup:

	<div id="content"<?php if ($contentHidden) echo ' class="hidden"'; ?>>
		...
	</div><!-- /#content -->

Instead, you will be able to use this simple syntax:

	<?php echo HTML::openHiddenArea('div', '#content', $contentHidden); ?>
		...
	<?php echo HTML::closeArea('div', '#content'); ?>

- [Installation](#installation)
- [Creating Dynamic Elements](#creating-dynamic-elements)
- [Creating Tables from Complex Arrays](#creating-tables)

<a name="installation"></a>
## Installation

To install Elemental, make sure `regulus/elemental` has been added to Laravel 5's `composer.json` file.

	"require": {
		"regulus/elemental": "dev-master"
	},

Then run `php composer.phar update` from the command line. Composer will install the Elemental package. Now, all you have to do is register the service provider and set up Elemental's alias in `config/app.php`. Add this to the `providers` array:

	'Regulus\Elemental\ElementalServiceProvider',

And add this to the `aliases` array:

	'HTML' => 'Regulus\Elemental\Facade',

You may use 'Elemental', or another alias, but 'HTML' is recommended for the sake of simplicity. Elemental is now ready to go.

<a name="creating-dynamic-elements"></a>
## Creating Dynamic Elements

**Creating a dynamic element that may have a "hidden" class:**

	//create opening tag entirely with Elemental
	echo HTML::openHiddenArea('div', array('id' => 'side-content', 'class' => 'content'), isset($sideContentHidden));

	{{-- set the hidden class depending on a boolean value within the opening tag --}}
	<div id="side-content"<?=HTML::hiddenArea(isset($sideContentHidden))?>>
		<p>Side Content</p>
	</div>

	{{-- set the hidden class depending on a boolean value within the opening tag's class attribute --}}
	<div id="side-content" class="content<?=HTML::hiddenArea(isset($sideContentHidden), true)?>"">
		<p>Side Content</p>
	</div>

The second argument of `openHiddenArea()` can contain a string for a single class or ID like ".class" or "#id" or it may contain an array of all the attributes you would like to use.

You may use `openActiveArea()` and `openSelectedArea()` which add "active" and "selected" classes respectively. All of these classes make use of the base method `openDynamicArea()` which includes the class name as its fourth argument. The three methods that make use of it simply exist as a short form to create some common dynamic elements. Additionally, for setting the class within the opening tag, you have `activeArea()`, `selectedArea()`, and `dynamicArea()`.

**Closing an element:**

	echo HTML::closeArea('div');

This method exists for the sake of completeness and also to prevent erroneous syntax highlighting in certain IDEs. If you open an HTML element in PHP, you should create your closing tag with PHP as well. You may include the class (".class") or ID ("#id") of the element as the second argument to have an HTML comment added to the line for the closing tag. This may help when trying to find where elements are opened and where they are closed in your source code.

**Closing an element and adding an identifying comment:**

	echo HTML::closeArea('div', '#content');

The above code outputs this:

	</div><!-- /#content -->

<a name="creating-tables"></a>
## Creating Tables from Complex Arrays

You may use Elemental to create data table markup including formatted headings, table body cells, icons, and much more. The best way to explain how this functions is by using an example that takes advantage of the majority of the `table()` method's features:

	$usersTable = array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.user')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'attribute' => 'username',
				'class'     => 'username',
				'sort'      => true,
			),
			array(
				'attribute' => 'name',
				'method'    => 'getName()',
				'sort'      => 'last_name',
			),
			array(
				'label'     => 'Email',
				'elements'  => array(
					array(
						'text' => ':email',
						'href' => 'mailto::email',
					),
				),
				'sort'      => 'email',
			),
			array(
				'label'     => 'Role(s)',
				'method'    => 'roles()',
				'attribute' => 'name',
				'type'      => 'list',
			),
			array(
				'label'     => 'Activated',
				'method'    => 'isActivated()',
				'type'      => 'boolean',
				'sort'      => true,
			),
			array(
				'label'     => 'Banned',
				'method'    => 'isBanned()',
				'type'      => 'boolean',
				'class'     => 'banned',
				'sort'      => true,
			),
			array(
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'dateTime',
				'sort'      => true,
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/users/:username/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editUser'),
						),
					),
					array(
						'icon'           => 'ban-circle',
						'class'          => 'action-item ban-user red',
						'classModifiers' => array(
							'hidden' => array(
								'isBanned()' => true,
							),
							'invisible' => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionBanUser',
							'data-action-message'  => 'confirmBanUser',
							'title'                => Lang::get('fractal::labels.banUser'),
						),
					),
					array(
						'icon'           => 'ok-circle',
						'class'          => 'action-item unban-user',
						'classModifiers' => array(
							'hidden'       => array(
								'isBanned()' => false,
							),
							'invisible'    => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionUnbanUser',
							'data-action-message'  => 'confirmUnbanUser',
							'title'                => Lang::get('fractal::labels.unbanUser'),
						),
					),
					array(
						'icon'           => 'remove',
						'class'          => 'action-item red',
						'classModifiers' => array(
							'invisible'    => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':username',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deleteUser'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix'       => 'user',
			'classModifiers' => array(
				'warning' => array(
					'isActivated()' => false,
				),
				'danger' => array(
					'isBanned()'    => true,
				),
			),
		),
	);

	//echo full table markup including headings
	echo HTML::table($usersTable, $users);

	//echo table body only, which is useful for returning an updated body by AJAX to replace the existing <tbody> element
	echo HTML::table($usersTable, $users, true);

This example is taken from the <a href="https://github.com/Regulus343/Fractal">Fractal</a> Laravel 5 CMS package which has many good examples of Elemental's table function in full use. Fractal contains use cases of both the originally rendered table, and rendered table bodies that are returned to the page via AJAX and update the table data based on the results of a search function.