<?php namespace app;

$baseconfig = CFS::config('mjolnir/base');

return array
	(
		'viewport' => 'width=device-width, initial-scale=1',
		'js-loader' => '//'.$baseconfig['domain'].$baseconfig['path'].'media/static/yepnope.latest-min.js',
	);
