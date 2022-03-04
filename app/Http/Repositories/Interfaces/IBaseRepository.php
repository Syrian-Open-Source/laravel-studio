<?php


namespace App\Http\Repositories\Interfaces;


use App\Exceptions\PublicException;
use App\Helpers\Classes\Response;
use App\Http\Requests\BaseRequest;
use App\Http\Resources\ResourceInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    /**
     * @param  mixed  $callback
     *
     * @return mixed|mixed
     * @author karam mustafa
     */
    public function get(\Closure $callback = null);

    /**
     * @param  $result
     *
     * @return mixed|mixed
     * @author karam mustafa
     */
    public function toPaginate($result = null);

    /**
     * @param  string  $resource
     * @param  string  $request
     * @param  \App\Http\Repositories\Interfaces\IBaseRepository[]  $any
     *
     * @return mixed|mixed
     * @author karam mustafa
     */
    public function registerDependencies(string $resource, string $request, IBaseRepository ...$any);

    /**
     * @return \App\Models\BaseModel
     * @author karam mustafa
     */
    public function getInstance();

    /**
     * specific model class
     * @return mixed|mixed
     * @author karam mustafa
     */
    function model();

    /**
     * basic create new element
     *
     * @param  array  $data
     *
     * @return Object|mixed|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function create(array $data);

    /**
     * basic shown element
     *
     * @param  int  $id
     * @param  string  $message
     *
     * @return mixed|string|mixed
     * @author karam mustafa
     */
    public function show($id, $message = null);

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
    public function update($id, $request, $message = null);

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
    public function delete($id, $message = null);

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
    public function find($id, \Closure $callback = null);

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
    public function findBy($col = 'id', $value = null, \Closure $callback = null);

    /**
     * bind model class
     *
     * @return ResponseFactory|Response|boolean|mixed
     * @throws PublicException
     * @author karam mustafa
     */
    public function makeModel();
}
