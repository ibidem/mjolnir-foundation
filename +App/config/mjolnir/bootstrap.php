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
								'development' => \app\CFS::config('mjolnir/base')['development'],
								'lang' => Lang::targetlang(),
								'langid' => Lang::idlang(\app\Lang::targetlang()),
								'urlbase' => $baseconfig['protocol'].$baseconfig['domain'].$baseconfig['path'],
								'urldomain' => $baseconfig['domain'],
								'urlpath' => $baseconfig['path'],
							),
					)
			),

	); # config
