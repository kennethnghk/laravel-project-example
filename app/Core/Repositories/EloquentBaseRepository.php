<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentBaseRepository
 *
 * @package \Core\Repositories\BaseScorer
 *
 * @see \Illuminate\Database\Eloquent\Builder
 */
abstract class EloquentBaseRepository extends BaseRepository
{
    const DEBUG = false;

    const MAIN_DATABASE_WRITE_CONNECTION = 'mysql::write';
    const MAIN_DATABASE_READ_CONNECTION = 'mysql::read';


    protected $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    protected function getClass()
    {
        return sprintf('%s', $this->className);
    }

    protected function getShortClass()
    {
        $class = $this->getClass();
        if (preg_match('@\\\\([\w]+)$@', $class, $matches)) {
            $class = $matches[1];
        }

        return $class;
    }

    /**
     * Begin querying the model on a given connection.
     *
     * @param string $connection
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function on($connection = null)
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $query = $class::on($connection);
        return $query;
    }

    /**
     * Begin querying the model on a given connection.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function onWriteConnection()
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $query = $class::on(self::MAIN_DATABASE_WRITE_CONNECTION);
        return $query;
    }

    /**
     * Begin querying the model on a given connection.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function onReadConnection()
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $query = $class::on(self::MAIN_DATABASE_READ_CONNECTION);
        return $query;
    }

    /**
     * Get all of the models from the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $items = $class::all($columns);

        return $items;
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($id, $columns = ['*'])
    {
        $class = $this->getClass();

        if (empty($id)) {
            \Log::error(__METHOD__ . sprintf("() [%s] No id input", $class));
            return null;
        }

        return $class::find($id, $columns);
    }

    /**
     * Get all of the models from the database with specified IDs
     *
     * @param array $values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByIds($values = [])
    {
        $class = $this->getClass();
        $values = array_unique($values);
        if (static::DEBUG) {
            \Log::debug(__METHOD__ . sprintf("() [%s] ids=%s", $class, json_encode($values)));
        }

        \Log::debug(__METHOD__ . sprintf("() CacheableModelInterface NOT_IMPLEMENTED class=%s", $class));

        if (empty($values)) {
            return new Collection();
        }

        $results = $class::whereIn($column = 'id', $values)
            ->orderByRaw("FIELD(id" . str_repeat(',?', count($values)) . ")", $values)
            ->get();

        $collection = new Collection($results);

        return $collection;
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function create($attributes = [])
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $item = $class::create($attributes);

        return $item;
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param array|int $ids
     *
     * @return int
     */
    public function destroy($ids = [])
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        $count = $class::destroy($ids);

        return $count;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param string|array $column
     * @param string       $operator
     * @param mixed        $value
     * @param string       $boolean
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $class = $this->getClass();
        $query = $class::where($column, $operator, $value, $boolean);

        return $query;
    }

    /**
     * Add a basic whereBetween clause to the query.
     *
     * @param string|array $column
     * @param array        $range
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereBetween($column, array $range)
    {
        $class = $this->getClass();
        $query = $class::whereBetween($column, $range);
        return $query;
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param string $column
     * @param mixed  $values
     * @param string $boolean
     * @param bool   $not
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $class = $this->getClass();

        $query = $class::whereIn($column, $values, $boolean, $not);

        return $query;
    }

    /**
     * Add a "where not null" clause to the query.
     *
     * @param string $column
     * @param string $boolean
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotNull($column, $boolean = 'and')
    {
        $class = $this->getClass();

        $query = $class::whereNotNull($column, $boolean);

        return $query;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        /**
         * @var \Illuminate\Database\Eloquent\Model
         */
        $class = $this->getClass();
        return $class::query();
    }

    /**
     * Set the "offset" value of the query.
     *
     * @param int $value
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function offset($value)
    {
        $class = $this->getClass();
        $query = $class::offset($value);
        return $query;
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param int $value
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function limit($value)
    {
        $class = $this->getClass();
        $query = $class::limit($value);
        return $query;
    }

    /**
     * Find the first element with a specific ID
     *
     * @param \Illuminate\Support\Collection $collection
     * @param mixed                          $value
     *
     * @return mixed|null
     */
    public function findByIdInCollection($collection, $value)
    {
        $key = 'id';
        return $this->findByKeyInCollection($collection, $key, $value);
    }

    /**
     * Find the first element with a specific value in a specific key
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string                         $key
     * @param mixed                          $value
     *
     * @return mixed|null
     */
    public function findByKeyInCollection($collection, $key, $value)
    {
        return $collection->filter(function ($item) use ($key, $value) {
            return $item->{$key} == $value;
        })->first();
    }

    /**
     * Group an associative array by a field or Closure value.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param callable|string                $groupBy
     *
     * @return array
     */
    public function indexByKeyInCollection($collection, $groupBy)
    {
        $results = [];

        $items = $collection->all();
        foreach ($items as $key => $value) {
            $key = is_callable($groupBy) ? $groupBy($value, $key) : array_get($value, $groupBy);
            $results[$key] = $value;
        }

        return $results;
    }

    /**
     * Partition a query into multiple queries each requesting a chunk of data
     *
     * Broken down queries are passed one by one to callback function.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int                                $batchSize  Result chunk size
     * @param callable                           $callback
     * @param int                                $batchSleep
     * @param int                                $retrySleep
     * @param int                                $maxRetry
     * @param int                                $start
     * @param int                                $end
     */
    public function queryInBatch(
        $query,
        $batchSize,
        $callback,
        $batchSleep = 0,
        $retrySleep = 3,
        $maxRetry = 3,
        $start = null,
        $end = null
    ) {
        if (is_null($start)) {
            $start = 0;
        }
        if (is_null($end)) {
            $end = $query->count() - $start;
        }

        \Log::info(__METHOD__ . " Getting start=$start end=$end");

        for ($offset = $start; $offset <= $end; $offset += $batchSize) {
            $this->executeWithDbRetry(function () use (
                $query,
                $batchSize,
                $callback,
                $batchSleep,
                $offset,
                $end
            ) {
                $actualBatchSize = ($offset + $batchSize > $end ? $end - $offset + 1 : $batchSize);
                \Log::info(__METHOD__ . " Getting offset=$offset limit=$actualBatchSize");

                $offsetQuery = $query->offset($offset)->limit($actualBatchSize);
                call_user_func($callback, $offsetQuery);
                if (!empty($batchSleep)) {
                    \Log::info(__METHOD__ . " Sleeping $batchSleep seconds...");
                    sleep($batchSleep);
                }
            }, $retrySleep, $maxRetry);
        }
    }

    /**
     * @param callable $callback
     * @param int      $retrySleep
     * @param int      $maxRetry
     *
     * @return mixed
     */
    public function executeWithDbRetry($callback, $retrySleep = 0, $maxRetry = 3)
    {
        $retryCount = 0;
        while ($retryCount < $maxRetry) {
            try {
                return call_user_func($callback);
            } catch (\Exception $e) {
                \Log::error("[" . get_class($this) . "] Failed with " . $e->getMessage());
                ++$retryCount;
                if ($e instanceof \Illuminate\Database\QueryException) {
                    \Log::error(__METHOD__ . sprintf(
                            '() RECONNECT_DB retryCount=[%s] maxRetry=[%s] %s ',
                            $retryCount,
                            $maxRetry,
                            $e
                        ));
                    sleep($retrySleep);
                    \Log::error(__METHOD__ . " Sleeping $retrySleep seconds...");
                    \DB::reconnect();
                }
            }
        }
    }
}
