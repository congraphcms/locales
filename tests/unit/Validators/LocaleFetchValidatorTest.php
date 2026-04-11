<?php

require_once __DIR__ . '/../../Fixtures/Commands/FakeLocaleRepository.php';

use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Locales\Commands\Locales\LocaleFetchCommand;
use Congraph\Locales\Validators\Locales\LocaleFetchValidator;

final class LocaleFetchValidatorTest extends \PHPUnit\Framework\TestCase
{
	public function testValidateDelegatesToRepositoryFetch(): void
	{
		$repository = new FakeLocaleRepository();
		$validator = new LocaleFetchValidator($repository);
		$command = new LocaleFetchCommand($repository);
		$command->setId('en_US');

		$validator->validate($command);

		$this->assertSame([['fetch', ['en_US', []]]], $repository->calls);
	}

	public function testValidateBubblesRepositoryErrors(): void
	{
		$this->expectException(NotFoundException::class);

		$repository = new FakeLocaleRepository();
		$repository->fetchException = new NotFoundException(['Missing locale']);

		$validator = new LocaleFetchValidator($repository);
		$command = new LocaleFetchCommand($repository);
		$command->setId('de_DE');

		$validator->validate($command);
	}
}
