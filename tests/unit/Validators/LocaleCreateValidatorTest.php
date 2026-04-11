<?php

require_once __DIR__ . '/../../Fixtures/Validators/ValidatorTestCase.php';

use Congraph\Core\Exceptions\ValidationException;
use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Repositories\LocaleRepository;
use Congraph\Locales\Validators\Locales\LocaleCreateValidator;
use Illuminate\Support\Facades\DB;

final class LocaleCreateValidatorTest extends ValidatorTestCase
{
	public function testValidatePassesForValidParamsAndCleansUnexpectedFields(): void
	{
		$validator = new LocaleCreateValidator();
		$repository = $this->app->make(LocaleRepository::class);
		$command = new LocaleCreateCommand($repository);
		$command->setParams([
			'code' => 'en_US',
			'name' => 'English',
			'description' => 'English language',
			'extra' => 'remove-me',
		]);

		$validator->validate($command);

		$this->assertSame([
			'code' => 'en_US',
			'name' => 'English',
			'description' => 'English language',
		], $command->params);
	}

	public function testValidateThrowsForInvalidParams(): void
	{
		$this->expectException(ValidationException::class);

		$validator = new LocaleCreateValidator();
		$repository = $this->app->make(LocaleRepository::class);
		$command = new LocaleCreateCommand($repository);
		$command->setParams([
			'code' => 'bad_code',
		]);

		$validator->validate($command);
	}

	public function testValidateThrowsForDuplicateCode(): void
	{
		DB::table('locales')->insert([
			'code' => 'en_US',
			'name' => 'English',
			'description' => 'English language',
		]);

		$this->expectException(ValidationException::class);

		$validator = new LocaleCreateValidator();
		$repository = $this->app->make(LocaleRepository::class);
		$command = new LocaleCreateCommand($repository);
		$command->setParams([
			'code' => 'en_US',
			'name' => 'English Duplicate',
		]);

		$validator->validate($command);
	}
}
