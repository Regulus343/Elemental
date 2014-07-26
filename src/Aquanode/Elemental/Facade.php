<?php namespace Aquanode\Elemental;

class Facade extends \Illuminate\Support\Facades\Facade {

	protected static function getFacadeAccessor() { return 'elemental'; }

}