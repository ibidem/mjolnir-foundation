<?php return array
	(
		'whitelist' => array
			(
				\app\Auth::Guest => array
					(
						\app\Allow::relays
							(
								'mjolnir:api-500.route',
								'mjolnir:api-404.route'
							)
							->unrestricted(),
					),

				'+mockup' => array
					(
						\app\Allow::relays
							(
								'mjolnir:mockup.route',
								'mjolnir:mockup-form.route',
								'mjolnir:mockup-errors.route'
							)
							->unrestricted(),
					),
			),
	);