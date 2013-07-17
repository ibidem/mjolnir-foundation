<?php namespace mjolnir\foundation;

/**
 * @package    mjolnir
 * @category   Foundation
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Application extends \app\Instantiatable implements \mjolnir\types\Application
{
	use \app\Trait_Application;

	/**
	 * ...
	 */
	function render()
	{
		$channel = $this->channel();

		try
		{
			foreach ($this->layers as $layer)
			{
				$layer->run();
			}

			$channel->preprocess();
			$channel->postprocess();

			return $channel->get('body', null);
		}
		catch (\Exception $exception)
		{
			if ($this->recoverymode)
			{
				\mjolnir\log_exception($exception);

				try
				{
					// reset all layers
					foreach ($this->layers as $layer)
					{
						$layer->reset();
					}

					$channel->set('exception', $exception);
					$channel->status_is(\app\Channel::error);

					foreach ($this->layers as $layer)
					{
						$layer->recover();
					}

					foreach ($this->layers as $layer)
					{
						$layer->run();
					}

					$channel->preprocess();
					$channel->postprocess();

					return $channel->get('body', null);
				}
				catch (\Exception $critical)
				{
					$this->criticalerror($critical);
				}
			}
			else # recoverymode == false
			{
				throw $exception;
			}
		}
	}

} # class
