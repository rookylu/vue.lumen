<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Transformers\MenuTransformer;

class MenuController extends BaseController
{
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function index()
    {
        $menus = $this->menu->paginate();

        return $this->response->paginator($menus, new MenuTransformer());
    }
    public function show($id)
    {
        $menu = $this->menu->findOrFail($id);

        return $this->response->item($menu, new MenuTransformer());
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:20',
            'route' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('name', 'route');
        $menu = $this->menu->create($attributes);

        //$location = dingo_route('v1', 'menus.show', $menu->id);
        // 返回 201 加数据
        return $this->response
            ->item($menu, new MenuTransformer())
            //->withHeader('Location', $location) // 可加可不加，参考 Http协议，但是大家一般不适用
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $menu = $this->menu->findOrFail($id);


        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:20',
            'route' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $menu->update($request->only('name', 'route'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $menu = $this->menu->findOrFail($id);

        $menu->delete();

        return $this->response->noContent();
    }
}
