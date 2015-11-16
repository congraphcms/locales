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


/**
 * LocaleCreateValidator class
 * 
 * Validating command for creating locale
 * 
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	cookbook/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleCreateValidator extends Validator
{


	/**
	 * Set of rules for validating locale
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * Create new LocaleCreateValidator
	 * 
	 * @return void
	 */
	public function __construct()
	{

		$this->rules = [
			'code'					=> [
				'required', 
				'unique:locales,code', 
				'regex:^[a-z]{2}(_[A-Z]{1}[a-z]{3})?(_[A-Z]{2})?$'
			],
			'name'					=> 'required|min:3|max:250',
			'description'			=> 'sometimes'
		];

		parent::__construct();

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
		$this->validateParams($command->params, $this->rules, true);

		if( $this->exception->hasErrors() )
		{
			throw $this->exception;
		}
	}
}