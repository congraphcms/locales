<?php

require_once __DIR__ . '/../../Fixtures/Commands/FakeLocaleRepository.php';

use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Locales\Commands\Locales\LocaleDeleteCommand;
use Congraph\Locales\Validators\Locales\LocaleDeleteValidator;

final class LocaleDeleteValidatorTest extends \PHPUnit\Framework\TestCase
{
	public function testValidateDelegatesToRepositoryFetch(): void
	{
		$repository = new FakeLocaleRepository();
		$validator = new LocaleDeleteValidator($repository);
		$command = new LocaleDeleteCommand($repository);
		$command->setId(7);

		$validator->validate($command);

		$this->assertSame([['fetch', [7, []]]], $repository->calls);
	}

	public function testValidateBubblesRepositoryErrors(): void
	{
		$this->expectException(NotFoundException::class);

		$repository = new FakeLocaleRepository();
		$repository->fetchException = new NotFoundException(['Missing locale']);

		$validator = new LocaleDeleteValidator($repository);
		$command = new LocaleDeleteCommand($repository);
		$command->setId(999);

		$validator->validate($command);
	}
}
