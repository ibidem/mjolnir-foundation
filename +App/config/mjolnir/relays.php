<?php namespace mjolnir\theme;

	$target =  [ 'target' => '[+a-zA-Z0-9\-\._/]+' ];

return array
	(

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