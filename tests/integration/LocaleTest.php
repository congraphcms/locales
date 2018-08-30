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
			'--realpath' => realpath(__DIR__.'/../database/migrations'),
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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');
		
		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleCreateCommand($params));
		
		$this->d->dump($result->toArray());
		$this->assertEquals('Czech', $result->name);
		$this->assertEquals('cz_CZ', $result->code);
		$this->assertEquals('Czech language', $result->description);
		
		$this->seeInDatabase('locales', [
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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');
		
		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleCreateCommand($params));
		
	}

	public function testUpdateLocale()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$params = [
			'code' => 'en_GB'
		];
		
		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleUpdateCommand($params, 1));
		
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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$params = [
			'code' => 'en_enene'
		];

		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleUpdateCommand($params, 1));
	}

	public function testDeleteLocale()
	{
		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleDeleteCommand([], 1));

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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleDeleteCommand([], 133));
	}
	
	public function testFetchLocale()
	{

		fwrite(STDOUT, __METHOD__ . "\n");

		$app = $this->createApplication();
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleFetchCommand([], 1));

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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleFetchCommand([], 'en_US'));

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
		$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');
		$result = $bus->dispatch( new Congraph\Locales\Commands\Locales\LocaleGetCommand([]));

		$this->assertTrue($result instanceof Congraph\Core\Repositories\Collection);
		$this->assertEquals(4, count($result));
		$this->d->dump($result->toArray());

	}

}