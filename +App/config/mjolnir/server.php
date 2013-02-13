<?php return array
	(
		'frontpage' => '/',
		'homepage' => function (array &$user)
			{
				return \app\Server::url_frontpage();
			},
	);