<?php return array
	(
		// place keys with function outputting json, the bootstrap will be 
		// included in all script calls and can be accessed via the Bootstrap 
		// variable
					
		'urlbase' => function ()
			{
				return '//'.\app\CFS::config('mjolnir/base')['domain'].\app\CFS::config('mjolnir/base')['path'];
			},
					
		'urldomain' => function ()
			{
				return \app\CFS::config('mjolnir/base')['domain'];
			},
					
		'urlpath' => function ()
			{
				return \app\CFS::config('mjolnir/base')['path'];
			},
	
	); # config
