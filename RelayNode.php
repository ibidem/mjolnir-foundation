<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class RelayNode extends \app\Instantiatable implements \mjolnir\types\RelayNode
{
	use \app\Trait_RelayNode;

	/**
	 * The source from a configuration can be passed to be used as metadata.
	 * 
	 * @return \mjolnir\types\RelayNode
	 */
	static function instance(array $source = null)
	{
		$instance = parent::instance();

		if ($source)
		{
			$instance->metadata_is($source);
			
			if ( ! \is_bool($source['matcher']))
			{
				$context = $source['matcher']->context();
			}
			else # no context
			{
				$context = null;
			}

			if ($context !== null)
			{
				foreach ($context as $key => $value)
				{
					$instance->set($key, $value);
				}
			}
		}

		return $instance;
	}

} # class
