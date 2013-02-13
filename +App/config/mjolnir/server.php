<?php return array
	(
		'frontpage' => '/',
		'dashboard' => function (array &$user)
			{
				return \app\Server::url_frontpage();
			},
	);