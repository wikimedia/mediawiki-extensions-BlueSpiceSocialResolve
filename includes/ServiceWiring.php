<?php

use MediaWiki\MediaWikiServices;

return [

	'BSSocialResolveFactory' => static function ( MediaWikiServices $services ) {
		return new \BlueSpice\Social\Resolve\Factory();
	},
];
