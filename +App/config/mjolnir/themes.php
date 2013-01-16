<?php return array
	(
		# This file also acts as default template for +theme files
	
		'theme.default' => 'classic',
		'themes.dir'    => 'themes', # relative to +App/
		'themes.config' => '+theme',

		// target-to-file mapping
		'mapping' => array
			(
				
			//// Exceptions ////////////////////////////////////////////////////

				'exception-NotFound' => array
					(
						'base/errors',
						'errors/not-found'
					),
				'exception-NotAllowed' => array
					(
						'base/errors',
						'errors/not-allowed'
					),
				'exception-NotApplicable' => array
					(
						'base/errors',
						'errors/not-applicable'
					),
				'exception-Unknown' => array
					(
						'base/errors',
						'errors/unknown'
					),
			),
	);
