<?php

require_once __DIR__ . '/../Fixtures/DatabaseTestCase.php';

use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Repositories\LocaleRepository;
use Congraph\Locales\Validators\Locales\LocaleCreateValidator;

final class LocalesServiceProviderTest extends DatabaseTestCase
{

	public function testImportantBindingsResolveFromContainer(): void
	{
		$this->assertInstanceOf(LocaleRepository::class, $this->app->make(LocaleRepository::class));
		$this->assertInstanceOf(LocaleRepositoryContract::class, $this->app->make(LocaleRepositoryContract::class));
		$this->assertInstanceOf(LocaleCreateCommand::class, $this->app->make(LocaleCreateCommand::class));
		$this->assertInstanceOf(LocaleCreateValidator::class, $this->app->make(LocaleCreateValidator::class));
	}
}
