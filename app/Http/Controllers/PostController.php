<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Interfaces\IPostRepository;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;

class PostController extends BaseController
{

    /**
     *
     * @author karam mustafa
     * @var \App\Http\Repositories\Interfaces\IPostRepository
     */
    protected IPostRepository $IPostRepository;

    /**
     * PostController constructor.
     *
     * @param  \App\Http\Repositories\Interfaces\IPostRepository  $IPostRepository
     */
    public function __construct(IPostRepository $IPostRepository)
    {

        $this->IPostRepository = $IPostRepository;
        $this->IPostRepository->registerDependencies(
            PostResource::class,
            PostRequest::class
            );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PublicException
     */
    public function index()
    {

        return $this->responseSuccess(
            $this->IPostRepository->toPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PublicException
     */
    public function store(PostRequest $request)
    {
        $this->IPostRepository->create($request->validated());

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PublicException
     */
    public function show($id)
    {
        return $this->responseSuccess(
            $this->IPostRepository->toPaginate(
                $this->IPostRepository->where('id', $id)
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PublicException
     */
    public function update(PostRequest $request, $id)
    {
        $this->IPostRepository->update($id, $request->validated());

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\PublicException
     */
    public function destroy($id)
    {
        $this->IPostRepository->delete($id);

        return $this->responseSuccess();
    }
}
