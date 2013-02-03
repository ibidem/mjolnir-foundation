<?php

$mjolnir_base = \app\CFS::config('mjolnir/base');

return array
	(
		'js-loader' => '//'.$mjolnir_base['domain'].$mjolnir_base['path'].'media/static/yepnope.latest-min.js',
	);
