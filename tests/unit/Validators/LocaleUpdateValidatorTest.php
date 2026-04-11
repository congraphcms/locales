<?php

require_once __DIR__ . '/../../Fixtures/Validators/ValidatorTestCase.php';

use Congraph\Core\Exceptions\ValidationException;
use Congraph\Locales\Commands\Locales\LocaleUpdateCommand;
use Congraph\Locales\Repositories\LocaleRepository;
use Congraph\Locales\Validators\Locales\LocaleUpdateValidator;
use Illuminate\Support\Facades\DB;

final class LocaleUpdateValidatorTest extends ValidatorTestCase
{
	public function testValidateAllowsKeepingExistingUniqueCodeForSameLocale(): void
	{
		DB::table('locales')->insert([
			'id' => 1,
			'code' => 'en_US',
			'name' => 'English',
			'description' => 'English language',
		]);

		$repository = $this->app->make(LocaleRepository::class);
		$validator = new LocaleUpdateValidator($repository);
		$command = new LocaleUpdateCommand($repository);
		$command->setId(1);
		$command->setParams([
			'code' => 'en_US',
			'name' => 'English Updated',
		]);

		$validator->validate($command);

		$this->assertSame('en_US', $command->params['code']);
		$this->assertSame('English Updated', $command->params['name']);
	}

	public function testValidateThrowsForInvalidUpdateCode(): void
	{
		DB::table('locales')->insert([
			'id' => 1,
			'code' => 'en_US',
			'name' => 'English',
			'description' => 'English language',
		]);

		$this->expectException(ValidationException::class);

		$repository = $this->app->make(LocaleRepository::class);
		$validator = new LocaleUpdateValidator($repository);
		$command = new LocaleUpdateCommand($repository);
		$command->setId(1);
		$command->setParams([
			'code' => 'bad_code',
		]);

		$validator->validate($command);
	}
}
