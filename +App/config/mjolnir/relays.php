<?php namespace mjolnir\theme;

	$theme =   [ 'theme' => '[a-zA-Z\-\._/]+' ];
	$style =   [ 'style' => '[a-zA-Z\-\._]+' ];
	$path =    [ 'path' => '.+' ]; # path is always validated internally
	$target =  [ 'target' => '[+a-zA-Z0-9\-\._/]+' ];
	$version = [ 'version' => '[0-9][a-z0-9-\.]*' ];

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

	// ---- Theme Drivers -----------------------------------------------------

	# javascripts

		'mjolnir:theme/driver/javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/<target>.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript',
			),

		'mjolnir:theme/driver/javascript-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/<target>.min.js.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript-map',
			),

		'mjolnir:theme/driver/javascript-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>-complete/master.js',
							$theme + $version
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete',
			),

		'mjolnir:theme/driver/javascript-complete-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>-complete/master.js.map',
							$theme + $version
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete-map',
			),



		'mjolnir:theme/driver/javascript-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/src/<target>.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript-source',
			),

		'mjolnir:theme/driver/javascript-bootstrap.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/bootstrap.js',
							$theme
						),

			// Theme Driver
				'theme.driver' => 'javascript-bootstrap',
			),

	# dart

		'mjolnir:theme/driver/dart.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart',
			),

		'mjolnir:theme/driver/dart-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-map',
			),

		'mjolnir:theme/driver/dart-javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript',
			),

		'mjolnir:theme/driver/dart-javascript-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.js.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript-map',
			),

	# css

		'mjolnir:theme/driver/css-style-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>/src/<target>.css',
							$theme + $style + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'css-style-source',
			),

		'mjolnir:theme/driver/css-style.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>/<target>.css',
							$theme + $style + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'css-style',
			),

		'mjolnir:theme/driver/css-style-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>-complete/master.css',
							$theme + $style + $version
						),

			// Theme Driver
				'theme.driver' => 'css-style-complete',
			),

		'mjolnir:theme/driver/style-resource.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<path>',
							$theme + $style + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'style-resource',
			),

	);