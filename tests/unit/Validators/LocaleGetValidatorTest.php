<?php

require_once __DIR__ . '/../../Fixtures/Validators/ValidatorTestCase.php';
require_once __DIR__ . '/../../Fixtures/Commands/FakeLocaleRepository.php';

use Congraph\Core\Exceptions\BadRequestException;
use Congraph\Locales\Commands\Locales\LocaleGetCommand;
use Congraph\Locales\Validators\Locales\LocaleGetValidator;

final class LocaleGetValidatorTest extends ValidatorTestCase
{
	public function testValidateNormalizesPagingAndInFilters(): void
	{
		$validator = new LocaleGetValidator();
		$command = new LocaleGetCommand(new FakeLocaleRepository());
		$command->setParams([
			'filter' => [
				'code' => [
					'in' => 'en_US,sr_RS',
				],
			],
			'offset' => '5',
			'limit' => '10',
			'sort' => '-created_at',
		]);

		$validator->validate($command);

		$this->assertSame(['en_US', 'sr_RS'], $command->params['filter']['code']['in']);
		$this->assertSame(5, $command->params['offset']);
		$this->assertSame(10, $command->params['limit']);
		$this->assertSame(['-created_at'], $command->params['sort']);
	}

	public function testValidateRejectsInvalidFilterField(): void
	{
		$this->expectException(BadRequestException::class);

		$validator = new LocaleGetValidator();
		$command = new LocaleGetCommand(new FakeLocaleRepository());
		$command->setParams([
			'filter' => [
				'unsupported' => 'value',
			],
		]);

		$validator->validate($command);
	}

	public function testValidateRejectsInvalidSortField(): void
	{
		$this->expectException(BadRequestException::class);

		$validator = new LocaleGetValidator();
		$command = new LocaleGetCommand(new FakeLocaleRepository());
		$command->setParams([
			'sort' => ['-unsupported'],
		]);

		$validator->validate($command);
	}
}
