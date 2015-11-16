<?php
/*
 * This file is part of the cookbook/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cookbook\Locales\Validators\Locales;

use Cookbook\Core\Bus\RepositoryCommand;
use Cookbook\Core\Validation\Validator;
use Cookbook\Contracts\Locales\LocaleRepositoryContract;


/**
 * LocaleFetchValidator class
 * 
 * Validating command for deleting locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleFetchValidator extends Validator
{
	/**
	 * Repository for locales
	 * 
	 * @var \Cookbook\Contracts\Locales\LocaleRepositoryContract
	 */
	protected $localeRepository;

	/**
	 * Create new LocaleFetchValidator
	 * 
	 * @return void
	 */
	public function __construct(LocaleRepositoryContract $localeRepository)
	{
		parent::__construct();
		$this->localeRepository = $localeRepository;
		$this->exception->setErrorKey('locale');
	}


	/**
	 * Validate RepositoryCommand
	 * 
	 * @param Cookbook\Core\Bus\RepositoryCommand $command
	 * 
	 * @todo  Create custom validation for all db related checks (DO THIS FOR ALL VALIDATORS)
	 * @todo  Check all db rules | make validators on repositories
	 * 
	 * @return void
	 */
	public function validate(RepositoryCommand $command)
	{
		$locale = $this->localeRepository->fetch($command->id);
	}
}