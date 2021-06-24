<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Commands\Locales;

use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Congraph\Core\Bus\RepositoryCommand;

/**
 * LocaleGetCommand class
 * 
 * Command for getting locales
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleGetCommand extends RepositoryCommand
{
    /**
	 * Create new LocaleGetCommand
	 * 
	 * @param Congraph\Contracts\Locales\LocaleRepositoryContract $repository
	 * 
	 * @return void
	 */
	public function __construct(LocaleRepositoryContract $repository)
	{
		parent::__construct($repository);
	}

	/**
	 * Handle RepositoryCommand
	 * 
	 * @return Congraph/Core/Repositories/Collection
	 */
	public function handle()
	{
		$locale = $this->repository->get(
			(!empty($this->params['filter']))?$this->params['filter']:[],
			(!empty($this->params['offset']))?$this->params['offset']:0,
			(!empty($this->params['limit']))?$this->params['limit']:0,
			(!empty($this->params['sort']))?$this->params['sort']:[],
			(!empty($this->params['include']))?$this->params['include']:[]
		);

		return $locale;
	}
}
