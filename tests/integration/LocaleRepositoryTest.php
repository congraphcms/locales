<?php

require_once __DIR__ . '/../Fixtures/DatabaseTestCase.php';

use Congraph\Contracts\Core\ObjectResolverContract;
use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Core\Repositories\Collection;
use Congraph\Core\Repositories\Model;
use Congraph\Locales\Repositories\LocaleRepository;

final class LocaleRepositoryTest extends DatabaseTestCase
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

	public function testFetchByCodeReturnsModelWithLocaleMetadata(): void
	{
		$repository = $this->app->make(LocaleRepository::class);

		$result = $repository->fetchByCode('en_US');

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame('locale', $result->type);
		$this->assertSame('en_US', $result->code);
		$this->assertSame('en_US', $result->getMeta('code'));
	}

	public function testFetchByCodeCanBeRepeated(): void
	{
		$repository = $this->app->make(LocaleRepository::class);

		$first = $repository->fetchByCode('en_US');
		$second = $repository->fetchByCode('en_US');

		$this->assertInstanceOf(Model::class, $first);
		$this->assertInstanceOf(Model::class, $second);
		$this->assertSame('en_US', $second->code);
	}

	public function testFetchByCodeThrowsForMissingLocale(): void
	{
		$this->expectException(NotFoundException::class);

		$this->app->make(LocaleRepository::class)->fetchByCode('de_DE');
	}

	public function testGetAppliesPagingFilteringAndMetadata(): void
	{
		$repository = $this->app->make(LocaleRepository::class);

		$result = $repository->get(['code' => 'en_US'], 0, 1, ['code'], []);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertCount(1, $result);
		$this->assertSame('en_US', $result[0]->code);
		$this->assertSame(1, $result->getMeta('count'));
		$this->assertSame(1, $result->getMeta('total'));
		$this->assertSame(['code' => 'en_US'], $result->getMeta('filter'));
		$this->assertSame(['code'], $result->getMeta('sort'));
	}

	public function testObjectResolverResolvesLocaleRepositoryMapping(): void
	{
		$result = $this->app->make(ObjectResolverContract::class)->resolve('locale', 1);

		$this->assertInstanceOf(Model::class, $result);
		$this->assertSame('en_US', $result->code);
	}
}
