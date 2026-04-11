<?php

require_once __DIR__ . '/../../Fixtures/Commands/FakeLocaleRepository.php';

use Congraph\Locales\Commands\Locales\LocaleCreateCommand;
use Congraph\Locales\Commands\Locales\LocaleDeleteCommand;
use Congraph\Locales\Commands\Locales\LocaleFetchCommand;
use Congraph\Locales\Commands\Locales\LocaleGetCommand;
use Congraph\Locales\Commands\Locales\LocaleUpdateCommand;

final class LocaleCommandsTest extends \PHPUnit\Framework\TestCase
{
	public function testCreateCommandDelegatesToRepositoryCreate(): void
	{
		$repository = new FakeLocaleRepository();
		$repository->createResult = (object) ['id' => 1, 'code' => 'en_US'];

		$command = new LocaleCreateCommand($repository);
		$command->setParams(['code' => 'en_US']);

		$result = $command->handle();

		$this->assertSame($repository->createResult, $result);
		$this->assertSame([['create', [['code' => 'en_US']]]], $repository->calls);
	}

	public function testUpdateCommandDelegatesToRepositoryUpdate(): void
	{
		$repository = new FakeLocaleRepository();
		$repository->updateResult = (object) ['id' => 1, 'code' => 'en_GB'];

		$command = new LocaleUpdateCommand($repository);
		$command->setId(1);
		$command->setParams(['code' => 'en_GB']);

		$result = $command->handle();

		$this->assertSame($repository->updateResult, $result);
		$this->assertSame([['update', [1, ['code' => 'en_GB']]]], $repository->calls);
	}

	public function testDeleteCommandReturnsDeletedLocaleId(): void
	{
		$repository = new FakeLocaleRepository();
		$repository->deleteResult = (object) ['id' => 7, 'code' => 'sr_RS'];

		$command = new LocaleDeleteCommand($repository);
		$command->setId(7);

		$result = $command->handle();

		$this->assertSame(7, $result);
		$this->assertSame([['delete', [7]]], $repository->calls);
	}

	public function testFetchCommandPassesIncludeParamsToRepository(): void
	{
		$repository = new FakeLocaleRepository();
		$repository->fetchResult = (object) ['id' => 1, 'code' => 'en_US'];

		$command = new LocaleFetchCommand($repository);
		$command->setId(1);
		$command->setParams(['include' => ['children']]);

		$result = $command->handle();

		$this->assertSame($repository->fetchResult, $result);
		$this->assertSame([['fetch', [1, ['children']]]], $repository->calls);
	}

	public function testGetCommandPassesDefaultsAndFiltersToRepository(): void
	{
		$repository = new FakeLocaleRepository();
		$repository->getResult = [(object) ['id' => 1, 'code' => 'en_US']];

		$command = new LocaleGetCommand($repository);
		$command->setParams([
			'filter' => ['code' => 'en_US'],
			'offset' => 5,
			'limit' => 10,
			'sort' => ['-created_at'],
			'include' => ['children'],
		]);

		$result = $command->handle();

		$this->assertSame($repository->getResult, $result);
		$this->assertSame([
			['get', [['code' => 'en_US'], 5, 10, ['-created_at'], ['children']]],
		], $repository->calls);
	}
}
