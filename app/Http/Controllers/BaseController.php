<?php

// app/Http/Controllers/BaseController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BaseRepositoryInterface;

class BaseController extends Controller
{
    protected BaseRepositoryInterface $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $items = $this->repository->all();
        return response()->json($items, 200);
    }

    public function store(Request $request)
    {
        $item = $this->repository->store($request->all());
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = $this->repository->find($id);
        return response()->json($item, 200);
    }

    public function update(Request $request, $id)
    {
        $item = $this->repository->update($request->all(), $id);
        return response()->json($item, 200);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json(null, 204);
    }
}
