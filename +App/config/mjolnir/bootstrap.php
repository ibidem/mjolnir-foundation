<?php namespace app;

$baseconfig = CFS::config('mjolnir/base');

return array
	(
		'mjolnir' => array
			(
				'config' => array
					(
						'base' => array
							(
								'lang' => Lang::targetlang(),
								'langid' => Lang::idlang(\app\Lang::targetlang()),
								'urlbase' => '//'.$baseconfig['domain'].$baseconfig['path'],
								'urldomain' => $baseconfig['domain'],
								'urlpath' => $baseconfig['path'],
							),
					)
			),

	); # config
