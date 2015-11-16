<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Debug\Dumper;

require_once(__DIR__ . '/../database/seeders/UserTestDbSeeder.php');
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
			'port'		=> '33060',
			'database'	=> 'cookbook_testbench',
			'username'  => 'homestead',
			'password'  => 'secret',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		]);

	}

	protected function getPackageProviders($app)
	{
		return ['Cookbook\Locales\LocalesServiceProvider', 'Cookbook\Core\CoreServiceProvider'];
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
		
		$result = $bus->dispatch( new Cookbook\Locales\Commands\Locales\LocaleCreateCommand($params));
		
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

	// /**
	//  * @expectedException \Cookbook\Core\Exceptions\ValidationException
	//  */
	// public function testCreateException()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$params = [
	// 		'name' => 'John Doe',
	// 		'email' => 'john.doe',
	// 		'password' => 'secret123'
	// 	];


	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');
		
	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserCreateCommand($params));
	// }

	// public function testUpdateUser()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

	// 	$params = [
	// 		'name' => 'Jane Margaret Doe'
	// 	];
		
	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserUpdateCommand($params, 1));
		
	// 	$this->assertTrue($result instanceof Cookbook\Core\Repositories\Model);
	// 	$this->assertTrue(is_int($result->id));
	// 	$this->assertEquals(1, $result->id);
	// 	$this->assertEquals('jane.doe@email.com', $result->email);
	// 	$this->assertEquals('Jane Margaret Doe', $result->name);
		
	// 	$this->d->dump($result->toArray());
	// }

	// /**
	//  * @expectedException \Cookbook\Core\Exceptions\NotFoundException
	//  */
	// public function testUpdateException()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

	// 	$params = [
			
	// 	];

	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserUpdateCommand($params, 1222));
	// }

	// public function testDeleteUser()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserDeleteCommand([], 1));

	// 	$this->assertEquals(1, $result);
	// 	$this->d->dump($result);

	// }

	// /**
	//  * @expectedException \Cookbook\Core\Exceptions\NotFoundException
	//  */
	// public function testDeleteException()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserDeleteCommand([], 133));
	// }
	
	// public function testFetchUser()
	// {

	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');

	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserFetchCommand([], 1));

	// 	$this->assertTrue($result instanceof Cookbook\Core\Repositories\Model);
	// 	$this->assertTrue(is_int($result->id));
	// 	$this->assertEquals('Jane Doe', $result->name);
	// 	$this->assertEquals('jane.doe@email.com', $result->email);
	// 	$this->d->dump($result->toArray());
		

	// }

	
	// public function testGetUsers()
	// {
	// 	fwrite(STDOUT, __METHOD__ . "\n");

	// 	$app = $this->createApplication();
	// 	$bus = $app->make('Illuminate\Contracts\Bus\Dispatcher');
	// 	$result = $bus->dispatch( new Cookbook\OAuth\Commands\Users\UserGetCommand([]));

	// 	$this->assertTrue($result instanceof Cookbook\Core\Repositories\Collection);
	// 	$this->assertEquals(count($result), 1);
	// 	$this->d->dump($result->toArray());

	// }

}