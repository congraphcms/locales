<?php
/*
 * This file is part of the congraph/locales package.
 *
 * (c) Nikola Plavšić <nikolaplavsic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Congraph\Locales\Repositories;

use Congraph\Contracts\Locales\LocaleRepositoryContract;
use Congraph\Core\Exceptions\Exception;
use Congraph\Core\Exceptions\NotFoundException;
use Congraph\Core\Facades\Trunk;
use Congraph\Core\Repositories\AbstractRepository;
use Congraph\Core\Repositories\Collection;
use Congraph\Core\Repositories\Model;
use Congraph\Core\Repositories\UsesCache;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

/**
 * LocaleRepository class
 * 
 * Repository for locales database queries
 * 
 * @uses   		Illuminate\Database\Connection
 * @uses   		Congraph\Core\Repository\AbstractRepository
 * 
 * @author  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @copyright  	Nikola Plavšić <nikolaplavsic@gmail.com>
 * @package 	congraph/locales
 * @since 		0.1.0-alpha
 * @version  	0.1.0-alpha
 */
class LocaleRepository extends AbstractRepository implements LocaleRepositoryContract//, UsesCache
{

// ----------------------------------------------------------------------------------------------
// PARAMS
// ----------------------------------------------------------------------------------------------
// 
// 
// 

	/**
	 * Create new LocaleRepository
	 * 
	 * @param Illuminate\Database\Connection $db
	 * 
	 * @return void
	 */
	public function __construct(Connection $db)
	{
		$this->type = 'locale';

		// AbstractRepository constructor
		parent::__construct($db);
	}

// ----------------------------------------------------------------------------------------------
// CRUD
// ----------------------------------------------------------------------------------------------
// 
// 
// 


	/**
	 * Create new locale
	 * 
	 * @param array $model - locale params (code, name, description...)
	 * 
	 * @return mixed
	 * 
	 * @throws Exception
	 */
	protected function _create($model)
	{
		$model['created_at'] = $model['updated_at'] = Carbon::now('UTC')->toDateTimeString();

		// insert locale in database
		$localeId = $this->db->table('locales')->insertGetId($model);

		// get locale
		$locale = $this->fetch($localeId);

		if(!$locale)
		{
			throw new \Exception('Failed to insert locale');
		}


		Cache::forget('locales');

		// and return newly created locale
		return $locale;
		
	}

	/**
	 * Update locale
	 * 
	 * @param array $model - locale params (code, name, description...)
	 *
	 * @return mixed
	 * 
	 * @throws Congraph\Core\Exceptions\NotFoundException
	 */
	protected function _update($id, $model)
	{

		// find locale with that ID
		$locale = $this->fetch($id);

		if( ! $locale )
		{
			throw new NotFoundException(['There is no locale with that ID.']);
		}

		$model['updated_at'] = Carbon::now('UTC')->toDateTimeString();

		$this->db->table('locales')->where('id', '=', $id)->update($model);

		Trunk::forgetType('locale');
		$locale = $this->fetch($id);
		Cache::forget('locales');

		// and return locale
		return $locale;
	}

	/**
	 * Delete locale from database
	 * 
	 * @param integer $id - ID of locale that will be deleted
	 * 
	 * @return boolean
	 * 
	 * @throws Congraph\Core\Exceptions\NotFoundException
	 */
	protected function _delete($id)
	{
		// get the locale
		$locale = $this->fetch($id);
		if(!$locale)
		{
			throw new NotFoundException(['There is no locale with that ID.']);
		}
		
		// delete the locale
		$this->db->table('locales')->where('id', '=', $locale->id)->delete();
		Trunk::forgetType('locale');
		Cache::forget('locales');
		return $locale;
	}
	


// ----------------------------------------------------------------------------------------------
// GETTERS
// ----------------------------------------------------------------------------------------------
// 
// 
// 

	/**
	 * Get locale by ID
	 * 
	 * @param int $id - ID of locale to be fetched
	 * 
	 * @return array
	 */
	protected function _fetch($id, $include = [])
	{
		$params = func_get_args();
		$params['function'] = __METHOD__;
		
		if(Trunk::has($params, 'locale'))
		{
			$locale = Trunk::get($id, 'locale');
			$locale->clearIncluded();
			$locale->load($include);
			$meta = ['id' => $id, 'include' => $include];
			$locale->setMeta($meta);
			return $locale;
		}
		
		if(is_string($id) && preg_match('/^[a-z]{2}(_[A-Z]{1}[a-z]{3})?(_[A-Z]{2})?$/', $id))
		{
			return $this->fetchByCode($id, $include);
		}

		$locale = $this->db->table('locales')->find($id);
		
		if( ! $locale )
		{
			throw new NotFoundException(['There is no locale with that ID.']);
		}

		$locale->type = 'locale';

		$timezone = (Config::get('app.timezone'))?Config::get('app.timezone'):'UTC';
		$locale->created_at = Carbon::parse($locale->created_at)->tz($timezone);
		$locale->updated_at = Carbon::parse($locale->updated_at)->tz($timezone);

		$result = new Model($locale);
		
		$result->setParams($params);
		$meta = ['id' => $id, 'include' => $include];
		$result->setMeta($meta);
		$result->load($include);
		return $result;
	}

	/**
	 * Get locales
	 * 
	 * @return array
	 */
	protected function _get($filter = [], $offset = 0, $limit = 0, $sort = [], $include = [])
	{
		$params = func_get_args();
		$params['function'] = __METHOD__;

		if(Trunk::has($params, 'locale'))
		{
			$locales = Trunk::get($params, 'locale');
			$locales->clearIncluded();
			$locales->load($include);
			$meta = [
				'include' => $include
			];
			$locales->setMeta($meta);
			return $locales;
		}

		$query = $this->db->table('locales');

		$query = $this->parseFilters($query, $filter);

		$total = $query->count();

		$query = $this->parsePaging($query, $offset, $limit);

		$query = $this->parseSorting($query, $sort);
		
		$locales = $query->get();

		if( ! $locales )
		{
			$locales = [];
		}
		
		foreach ($locales as &$locale) {
			$locale->type = 'locale';
			$timezone = (Config::get('app.timezone'))?Config::get('app.timezone'):'UTC';
			$locale->created_at = Carbon::parse($locale->created_at)->tz($timezone);
			$locale->updated_at = Carbon::parse($locale->updated_at)->tz($timezone);
		}

		$result = new Collection($locales);
		
		$result->setParams($params);

		$meta = [
			'count' => count($locales), 
			'offset' => $offset, 
			'limit' => $limit, 
			'total' => $total, 
			'filter' => $filter, 
			'sort' => $sort, 
			'include' => $include
		];
		$result->setMeta($meta);

		$result->load($include);
		
		return $result;
	}

	/**
	 * Get locale by code
	 * 
	 * @param int $code - code of locale to be fetched
	 * 
	 * @return array
	 */
	public function fetchByCode($code, $include = [])
	{
		$params = func_get_args();
		$params['function'] = __METHOD__;
		
		if(Trunk::has($params, 'locale'))
		{
			$locale = Trunk::get($id, 'locale');
			$locale->clearIncluded();
			$locale->load($include);
			$meta = ['code' => $code, 'include' => $include];
			$locale->setMeta($meta);
			return $locale;
		}

		$locale = $this->db->table('locales')->where('code', '=', $code)->first();
		
		if( ! $locale )
		{
			throw new NotFoundException(['There is no locale with that code.']);
		}

		$locale->type = 'locale';

		$timezone = (Config::get('app.timezone'))?Config::get('app.timezone'):'UTC';
		$locale->created_at = Carbon::parse($locale->created_at)->tz($timezone);
		$locale->updated_at = Carbon::parse($locale->updated_at)->tz($timezone);

		$result = new Model($locale);
		
		$result->setParams($params);
		$meta = ['code' => $code, 'include' => $include];
		$result->setMeta($meta);
		$result->load($include);
		return $result;
	}

}