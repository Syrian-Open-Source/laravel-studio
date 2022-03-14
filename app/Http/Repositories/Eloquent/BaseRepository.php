<?php


namespace App\Http\Repositories\Eloquent;


use App\Exceptions\PublicException;
use App\Helpers\Classes\Response;
use App\Http\Repositories\Interfaces\IBaseRepository;
use App\Http\Requests\BaseRequest;
use App\Http\Resources\ResourceInterface;
use Illuminate\Container\Container as App;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 *
 * @author karam mustafa
 * @package App\Http\Repositories\Eloquent
 */
abstract class BaseRepository implements IBaseRepository
{
    use Response;

    /**
     * @var App
     */
    private App $app;
    /**
     * @var mixed
     */
    public Model $model;
    /**
     *
     * @author karam mustafa
     * @var string
     */
    private string $requestClass;
    /**
     *
     * @author karam mustafa
     * @var string
     */
    private string $resourceClass;

    /**
     * BaseRepository constructor.
     *
     * @param  \Illuminate\Container\Container  $app
     *
     * @throws \App\Exceptions\PublicException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     *
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @author karam mustafa
     */
    public function __call($name, $arguments)
    {
        return $this->getInstance()->{$name}(...$arguments);
    }

    /**
     * @param  string  $resource
     * @param  string  $request
     * @param  \App\Http\Repositories\Interfaces\IBaseRepository[]  $any
     *
     * @return mixed
     * @author karam mustafa
     */
    public function registerDependencies(string $resource, string $request, IBaseRepository ...$any)
    {
        $this->resourceClass = $resource;
        $this->requestClass = $request;
    }

    /**
     * @param  mixed  $callback
     *
     * @return mixed
     * @author karam mustafa
     */
    public function get(\Closure $callback = null)
    {
        $model = $this->model;

        // Check if user pass any callback, the return this model within callback
        if ($callback != null) {
            return $callback($model);
        }

        $query = $model->orderBy('created_at', 'desc');

        return $query->paginate($this->resolvePaginationCount($query));
    }

    /**
     * @param  Builder|\Illuminate\Pagination\LengthAwarePaginator|Model  $query
     *
     * @return mixed
     * @author karam mustafa
     */
    public function toPaginate($query = null)
    {
        $query = $query instanceof Builder
            ? $query->paginate($this->resolvePaginationCount($query))
            : $query;

        return (new $this->resourceClass($query != null ? $query : $this->get()));
    }

    /**
     * @return mixed
     * @author karam mustafa
     */
    public function getInstance()
    {
        return new $this->model;
    }

    /**
     * specific model class
     * @return mixed
     * @author karam mustafa
     */
    abstract function model();

    /**
     * basic create new element
     *
     * @param  array  $data
     *
     * @return Object|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function create($data = [])
    {
        try {
            return $this->model::create($this->resolveRequestDataToCreate($data));

        } catch (\Exception $e) {
            return throwExceptionResponse(__CLASS__, __LINE__, $e->getMessage(), true);
        }
    }

    /**
     * basic shown element
     *
     * @param  integer  $id
     * @param  string  $message
     *
     * @return mixed|string
     * @throws PublicException
     * @author karam mustafa
     */
    public function show($id, $message = null)
    {
        try {
            return $this->find($id, function (Model $item) {
                return $item;
            });

        } catch (\Exception $e) {
            return throwExceptionResponse(__CLASS__, __LINE__, $e->getMessage(), true);
        }
    }

    /**
     * basic update element
     *
     * @param  integer  $id
     * @param  array  $request
     * @param  string  $message
     *
     * @return ResponseFactory|Response|boolean|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function update($id, $request, $message = null)
    {
        try {
            return $this->find($id, function (Model $item) use ($request) {
                return $item->update($request);
            });

        } catch (\Exception $e) {
            return throwExceptionResponse(__CLASS__, __LINE__, $e->getMessage(), true);
        }
    }

    /**
     * basic delete element
     *
     * @param  integer  $id
     * @param  string  $message
     *
     * @return Response|bool|ResponseFactory|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function delete($id, $message = null)
    {
        try {
            return $this->find($id, function (Model $item) {
                return $item->delete();
            });

        } catch (\Exception $e) {
            return throwExceptionResponse(__CLASS__, __LINE__, $e->getMessage(), true);
        }
    }

    /**
     * basic find element
     *
     * @param  integer  $id
     * @param  \Closure  $callback
     *
     * @return ResponseFactory|Response|boolean|mixed
     * @throws \Exception
     * @author karam mustafa
     */
    public function find($id, \Closure $callback = null)
    {
        $model = $this->model::find($id);
        //check if this table has custom id
        if (isset($model)) {

            return $callback != null ? $callback($model) : $model;

        }
        throw new \Exception(myTrans('not_found_resource'), $this->NOT_FOUND);
    }

    /**
     * basic find element
     *
     * @param  string  $col
     * @param  string  $value
     * @param  \Closure  $callback
     *
     * @return ResponseFactory|Response|boolean|mixed
     * @throws \Exception
     * @author karam mustafa
     */
    public function findBy($col = 'id', $value = null, \Closure $callback = null)
    {
        $model = $this->model::where($col, $value)->get();

        //check if this table has custom col
        if (isset($model) && sizeof($model)) {

            return $callback != null ? $callback($model) : $model;

        }
        throw new \Exception(myTrans('not_found_resource'),$this->NOT_FOUND);
    }

    /**
     * bind model class
     * @return ResponseFactory|Response|boolean|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function makeModel()
    {
        try {
            $model = $this->app->make($this->model());
            return $this->model = $model;
        } catch (\Exception $e) {
            return throwExceptionResponse(__CLASS__, __LINE__, $e->getMessage(), true);
        }
    }

    /**
     * this function will check if the request contains an image
     * then we will save this image and return the path that we save the image within.
     * you can add a new image keys in public config file.
     *
     * @param $request
     *
     * @return mixed
     * @throws \App\Exceptions\PublicException
     * @author karam mustafa
     */
    private function resolveRequestDataToCreate($request)
    {

        $imagesKeys = config('public.images_keys_in_request') ?? [];

        foreach ($imagesKeys as $key) {
            if (isset($request[$key]) && $request[$key] != '') {
                $request[$key] = saveImage($this->model, request(), $key);
            }
        }

        return $request;
    }

    /**
     * check if the request has an all value in the limit parameter,
     * then we will return count data of selected model
     * else we will determine the amount of data requested.
     *
     * @param   $query
     *
     * @return \Illuminate\Config\Repository|mixed
     * @author karam mustafa
     */
    private function resolvePaginationCount(Builder $query)
    {
        $requestIsForAll = request()->has('limit') && request()->get('limit') == 'all';

        if ($requestIsForAll) {
            return $query->count();
        }
        return request()->has('limit') ? request()->get('limit') : config('public.pagination');
    }
}
