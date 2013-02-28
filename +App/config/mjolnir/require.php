<?php namespace mjolnir\theme;

return array
	(
		'mjolnir\foundation' => array
			(
				 'extension=php_curl' => function ()
					{
						if (\extension_loaded('curl'))
						{
							return 'available';
						}

						return 'error';
					}
			),
	);
