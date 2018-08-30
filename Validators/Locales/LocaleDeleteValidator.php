<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Validators\Locales;

use Congraph\Core\Bus\RepositoryCommand;
use Congraph\Core\Validation\Validator;
use Congraph\Contracts\Locales\LocaleRepositoryContract;


/**
 * LocaleDeleteValidator class
 * 
 * Validating command for deleting locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleDeleteValidator extends Validator
{
	/**
	 * Repository for locales
	 * 
	 * @var \Congraph\Contracts\Locales\LocaleRepositoryContract
	 */
	protected $localeRepository;

	/**
	 * Create new LocaleDeleteValidator
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
	 * @param Congraph\Core\Bus\RepositoryCommand $command
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