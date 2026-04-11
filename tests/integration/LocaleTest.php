<?php

require_once __DIR__ . '/../Fixtures/DatabaseTestCase.php';

use Congraph\Core\Bus\CommandDispatcher;
use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Core\Exceptions\ValidationException;
use Congraph\Core\Repositories\Collection;
use Congraph\Core\Repositories\Model;
use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Commands\Locales\LocaleDeleteCommand;
use Congraph\Locales\Commands\Locales\LocaleFetchCommand;
use Congraph\Locales\Commands\Locales\LocaleGetCommand;
use Congraph\Locales\Commands\Locales\LocaleUpdateCommand;

final class LocaleTest extends DatabaseTestCase
{
	protected function seeders(): array
	{
		return [
			Database\Seeders\LocaleTestDbSeeder::class,
		];
	}

	protected function cleanupSeeders(): array
	{
		return [
			Database\Seeders\ClearDB::class,
		];
	}

	public function testCreateLocale(): void
	{
		$command = $this->app->make(LocaleCreateCommand::class);
		$command->setParams([
			'code' => 'cz_CZ',
			'name' => 'Czech',
			'description' => 'Czech language',
		]);

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame('Czech', $result->name);
		$this->assertSame('cz_CZ', $result->code);
		$this->assertSame('Czech language', $result->description);

		$this->assertDatabaseHas('locales', [
			'code' => 'cz_CZ',
			'name' => 'Czech',
			'description' => 'Czech language',
		]);
	}

	public function testCreateLocaleThrowsValidationExceptionForInvalidCode(): void
	{
		$this->expectException(ValidationException::class);

		$command = $this->app->make(LocaleCreateCommand::class);
		$command->setParams([
			'code' => 'en_enneene',
			'name' => 'English',
		]);

		$this->dispatch($command);
	}

	public function testUpdateLocale(): void
	{
		$command = $this->app->make(LocaleUpdateCommand::class);
		$command->setId(1);
		$command->setParams([
			'code' => 'en_GB',
		]);

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame(1, $result->id);
		$this->assertSame('en_GB', $result->code);
		$this->assertSame('English', $result->name);
	}

	public function testUpdateLocaleThrowsValidationExceptionForInvalidCode(): void
	{
		$this->expectException(ValidationException::class);

		$command = $this->app->make(LocaleUpdateCommand::class);
		$command->setId(1);
		$command->setParams([
			'code' => 'en_enene',
		]);

		$this->dispatch($command);
	}

	public function testDeleteLocale(): void
	{
		$command = $this->app->make(LocaleDeleteCommand::class);
		$command->setId(1);

		$result = $this->dispatch($command);

		$this->assertSame(1, $result);
		$this->assertDatabaseMissing('locales', ['id' => 1]);
	}

	public function testDeleteLocaleThrowsWhenLocaleDoesNotExist(): void
	{
		$this->expectException(NotFoundException::class);

		$command = $this->app->make(LocaleDeleteCommand::class);
		$command->setId(133);

		$this->dispatch($command);
	}

	public function testFetchLocaleById(): void
	{
		$command = $this->app->make(LocaleFetchCommand::class);
		$command->setId(1);

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame('en_US', $result->code);
		$this->assertSame('English', $result->name);
	}

	public function testFetchLocaleByIdCanBeRepeated(): void
	{
		$command = $this->app->make(LocaleFetchCommand::class);
		$command->setId(1);

		$first = $this->dispatch($command);
		$second = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $first);
		$this->assertInstanceOf(Model::class, $second);
		$this->assertSame('en_US', $second->code);
	}

	public function testFetchLocaleByCode(): void
	{
		$command = $this->app->make(LocaleFetchCommand::class);
		$command->setId('en_US');

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame('en_US', $result->code);
		$this->assertSame('English', $result->name);
	}

	public function testFetchLocaleByCodeCanBeRepeated(): void
	{
		$command = $this->app->make(LocaleFetchCommand::class);
		$command->setId('en_US');

		$first = $this->dispatch($command);
		$second = $this->dispatch($command);

		$this->assertInstanceOf(Model::class, $first);
		$this->assertInstanceOf(Model::class, $second);
		$this->assertSame('en_US', $second->code);
	}

	public function testGetLocalesReturnsCollection(): void
	{
		$command = $this->app->make(LocaleGetCommand::class);

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertCount(4, $result);
	}

	public function testGetLocalesCanBeFiltered(): void
	{
		$command = $this->app->make(LocaleGetCommand::class);
		$command->setParams([
			'filter' => [
				'code' => 'en_US',
			],
		]);

		$result = $this->dispatch($command);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertCount(1, $result);
		$this->assertSame('en_US', $result[0]->code);
	}

	private function dispatch(object $command): mixed
	{
		return $this->app->make(CommandDispatcher::class)->dispatch($command);
	}
}
