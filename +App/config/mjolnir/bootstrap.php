<?php return array
	(			
		'mjolnir' => array
			(
				'config' => array
					(
						'base' => array
							(
								'lang' => \app\Lang::targetlang(),
								'langid' => \app\Lang::idlang(\app\Lang::targetlang()),
								'urlbase' => '//'.\app\CFS::config('mjolnir/base')['domain'].\app\CFS::config('mjolnir/base')['path'],
								'urldomain' => \app\CFS::config('mjolnir/base')['domain'],
								'urlpath' => \app\CFS::config('mjolnir/base')['path'],
							),
					)
			),
	
	); # config
