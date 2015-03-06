<?php namespace Regulus\Elemental;

class Facade extends \Illuminate\Support\Facades\Facade {

	protected static function getFacadeAccessor() { return 'Regulus\Elemental\Elemental'; }

}