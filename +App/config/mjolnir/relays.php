<?php namespace mjolnir\theme;

	$target =  [ 'target' => '[+a-zA-Z0-9\-\._/]+' ];

return array
	(

		'mjolnir:bootstrap.route' => array
			(
					'matcher' => \app\URLRoute::instance()
						->urlpattern('system/bootstrap.json'),
					'enabled' => true,
					// MVC
					'controller' => '\app\Controller_Bootstrap',
					'prefix' => 'json_',
					'action' => 'index',
			),

	// ---- Error Handing Relays ----------------------------------------------

		'mjolnir:error/log.route' => array
			(
					'matcher' => \app\URLRoute::instance()
					->urlpattern('error-log'),
					'enabled' => true,
					// MVC
					'controller' => '\app\Controller_Error',
					'action' => 'log',
					'prefix' => 'action_',
			),

		'mjolnir:api-json-500.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('api/500.json'),
				'enabled' => true, # intentional
			// MVC
				'controller' => '\app\Controller_Error',
				'action' => '500',
				'prefix' => 'json_',
			),

		'mjolnir:api-jsend-500.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('api/500.jsend'),
				'enabled' => true, # intentional
			// MVC
				'controller' => '\app\Controller_Error',
				'action' => '500',
				'prefix' => 'jsend_',
			),

		'mjolnir:api-404.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('api/<anything>', ['anything' => '.+']),
				'enabled' => true, # intentional
			// MVC
				'controller' => '\app\Controller_Error',
				'action' => '404',
				'prefix' => 'api_',
			),

	// ---- Mockup ------------------------------------------------------------

			'mjolnir:mockup.route' => array
			(
					'matcher' => \app\URLRoute::instance()
					->urlpattern('mockup/<target>', $target),
					'enabled' => false,
					// MVC
					'controller' => '\app\Controller_Mockup',
					'action' => 'action_testing',
			),

			'mjolnir:mockup-errors.route' => array
			(
					'matcher' => \app\URLRoute::instance()
					->urlpattern('mockup-errors/<target>', $target),
					'enabled' => false,
					// MVC
					'controller' => '\app\Controller_Mockup',
					'action' => 'action_errortesting',
			),

			'mjolnir:mockup-form.route' => array
			(
					'matcher' => \app\URLRoute::instance()
					->urlpattern('form-mockup'),
					'enabled' => false,
					// MVC
					'controller' => '\app\Controller_Mockup',
					'action' => 'action_form',
			),

	);