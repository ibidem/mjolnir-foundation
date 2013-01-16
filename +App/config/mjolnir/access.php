<?php return array
	(
		'whitelist' => array
			(
				'+mockup' => array
					(
						\app\Allow::relays
							(
								'mjolnir:mockup.route',
								'mjolnir:mockup-form.route',
								'mjolnir:mockup-errors.route'
							)
							->all_parameters(),
					),
			),
	);