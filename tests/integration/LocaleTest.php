<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;

require_once(__DIR__ . '/../database/seeders/LocaleTestDbSeeder.php');
require_once(__DIR__ . '/../database/seeders/ClearDB.php');

class LocaleTest extends Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->artisan('migrate', [
			'--database' => 'testbench',
			'--path' => realpath(__DIR__.'/../database/migrations'),
		]);

		$this->artisan('db:seed', [
			'--class' => 'LocaleTestDbSeeder'
		]);

		$this->d = new Dumper();


	}

	public function tearDown()
	{
		$this->artisan('db:seed', [
			'--class' => 'ClearDB'
		]);
		parent::tearDown();
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 *
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'testbench');
		$app['config']->set('database.connections.testbench', [
			'driver'   	=> 'mysql',
			'host'      => '127.0.0.1',
			'port'		=> '3306',
			'database'	=> 'congraph_testbench',
			'username'  => 'root',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		]);

	}

	protected function getPackageProviders($app)
	{
		return ['Congraph\Locales\LocalesServiceProvider', 'Congraph\Core\CoreServiceProvider'];
	}

	public function testCreateLocale()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'cz_CZ',
			'name' => 'Czech',
			'description' => 'Czech language'
		];


		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');
		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleCreateCommand::class);
		$command->setParams($params);
		
		$result = $bus->dispatch($command);
		
		$this->d->dump($result->toArray());
		$this->assertEquals('Czech', $result->name);
		$this->assertEquals('cz_CZ', $result->code);
		$this->assertEquals('Czech language', $result->description);
		
		$this->assertDatabaseHas('locales', [
			'id' => 5, 
			'code' => 'cz_CZ', 
			'name' => 'Czech',
			'description' => 'Czech language'
		]);
	}

	/**
	 * @expectedException \Congraph\Core\Exceptions\ValidationException
	 */
	public function testCreateException()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$params = [
			'code' => 'en_enneene',
			'name' => 'English'
		];


		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleCreateCommand::class);
		$command->setParams($params);
		
		$result = $bus->dispatch($command);
		
	}

	public function testUpdateLocale()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$params = [
			'code' => 'en_GB'
		];

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleUpdateCommand::class);
		$command->setParams($params);
		$command->setId(1);
		
		$result = $bus->dispatch($command);
		
		$this->assertTrue($result instanceof Congraph\Core\Repositories\Model);
		$this->assertTrue(is_int($result->id));
		$this->assertEquals(1, $result->id);
		$this->assertEquals('en_GB', $result->code);
		$this->assertEquals('English', $result->name);
		
		$this->d->dump($result->toArray());
	}

	/**
	 * @expectedException \Congraph\Core\Exceptions\ValidationException
	 */
	public function testUpdateException()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$params = [
			'code' => 'en_enene'
		];

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleUpdateCommand::class);
		$command->setParams($params);
		$command->setId(1);
		
		$result = $bus->dispatch($command);
		$this->d->dump($result->toArray());
		
	}

	public function testDeleteLocale()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleDeleteCommand::class);
		$command->setId(1);
		
		$result = $bus->dispatch($command);

		$this->assertEquals(1, $result);
		$this->d->dump($result);

	}

	/**
	 * @expectedException \Congraph\Core\Exceptions\NotFoundException
	 */
	public function testDeleteException()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleDeleteCommand::class);
		$command->setId(133);
		
		$result = $bus->dispatch($command);
	}
	
	public function testFetchLocale()
	{

		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleFetchCommand::class);
		$command->setId(1);
		
		$result = $bus->dispatch($command);

		$this->assertTrue($result instanceof Congraph\Core\Repositories\Model);
		$this->assertTrue(is_int($result->id));
		$this->assertEquals('en_US', $result->code);
		$this->assertEquals('English', $result->name);
		$this->d->dump($result->toArray());
	}

	public function testFetchLocaleByCode()
	{

		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleFetchCommand::class);
		$command->setId('en_US');
		
		$result = $bus->dispatch($command);

		$this->assertTrue($result instanceof Congraph\Core\Repositories\Model);
		$this->assertTrue(is_int($result->id));
		$this->assertEquals('en_US', $result->code);
		$this->assertEquals('English', $result->name);
		$this->d->dump($result->toArray());
	}

	
	public function testGetLocales()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Congraph\Core\Bus\CommandDispatcher');

		$command = $app->make(\Congraph\Locales\Commands\Locales\LocaleGetCommand::class);
		
		$result = $bus->dispatch($command);

		$this->assertTrue($result instanceof Congraph\Core\Repositories\Collection);
		$this->assertEquals(4, count($result));
		$this->d->dump($result->toArray());

	}

}