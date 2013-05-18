<?php return array
	(
		# [!!] these names also act as extentions in routing and prefixes in
		# controllers! we recomend you keep them simple

		// general purpose stack with domain access control, header processing
		// and a typical MVC structure (public here means "people")
		'public' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_HTML::instance(),
						\app\Layer_Theme::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// logging stack; logging operations typically don't involve domain
		// level access control, if they do they should just use a mvc stack
		// instead of this one
		'log' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// like the public stack only html's head section, scripts and so on are
		// processed at the MVC level
		'html' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_Theme::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// like html, only general purpose
		'raw' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_Theme::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// jsend is a json based communication protocol; the mvc layer can
		// output PHP variables (strings, arrays, etc) and they'll be wrapped
		// in the jsend protocol; exceptions will also trigger appropriate
		// behaviour
		'jsend' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_JSend::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// basic json
		'json' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_JSON::instance(),
						\app\Layer_MVC::instance()
							->set('skip-error-handling', true)
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// basic json
		'csv' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Access::instance(),
						\app\Layer_CSV::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// stack used to resolve relay'ed routes with theme drivers attached
		'resource' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relay)
					->set('relaykey', $target);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Resource::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},
	);