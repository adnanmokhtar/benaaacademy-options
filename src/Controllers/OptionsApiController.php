<?php

namespace Benaaacademy\Options\Controllers;

use Benaaacademy\Options\Models\Option;
use Benaaacademy\Platform\APIController;
use Illuminate\Http\Request;

/**
 * Class OptionsApiController
 */
class OptionsApiController extends APIController
{

    /**
     * OptionsApiController constructor.
     */
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware("permission:options");
    }

    /**
     * List posts
     * @param string $name (optional) The option name.
     * @param string $q (optional) The search query string.
     * @param int $limit (default: 10) The number of retrieved records.
     * @param int $page (default: 1) The page number.
     * @param string $order_by (default: id) The column you wish to sort by.
     * @param string $order_direction (default: DESC) The sort direction ASC or DESC.
     * @return \Illuminate\Http\JsonResponse
     */
    function show(Request $request)
    {

        $name = $request->get("name");
        $limit = $request->get("limit", 10);
        $sort_by = $request->get("sort_by", "id");
        $sort_direction = $request->get("sort_direction", "DESC");

        $query = Option::orderBy($sort_by, $sort_direction);

        if ($request->filled("q")) {
            $query->search($request->get("q"));
        }

        if ($name) {
            $options = @$query->where("name", $name)->pluck("value")[0];
        } else {
            $options = $query->paginate($limit)->appends($request->all());
        }


        return $this->response($options);

    }


    /**
     * Create a new option
     * @param string $name (required) The option name.
     * @param string $value (required) The post value.
     * @return \Illuminate\Http\JsonResponse
     */
    function create(Request $request)
    {

        if (!$request->filled("name")) {
            return $this->error("Missing option name");
        }

        if (!$request->filled("value")) {
            return $this->error("Missing option value for " . $request->name);
        }

        Option::store([
            $request->name => $request->value
        ]);

        return $this->response(["name" => $request->get("name"), "value" => $request->get("value")]);

    }

    /**
     * Update an option
     * @param string $name (required) The option name.
     * @param string $value (optional) The option value.
     * @return \Illuminate\Http\JsonResponse
     */
    function update(Request $request)
    {

        if (!$request->filled("name")) {
            return $this->error("Missing option name");
        }

        if (!$request->filled("value")) {
            return $this->error("Missing option value");
        }

        $option = Option::where("name", $request->name)->first();

        if (!$option) {
            return $this->error("Option [" . $request->name . "] is not exists");
        }

        Option::store([
            $request->name => $request->value
        ]);

        return $this->response(["name" => $request->get("name"), "value" => $request->get("value")]);

    }

    /**
     * Delete post by id
     * @param int $name (required) The option name.
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(Request $request)
    {

        if (!$request->filled("name")) {
            return $this->error("Missing option name");
        }

        $option = Option::where("name", $request->name)->first();

        if (!$option) {
            return $this->error("Option [" . $request->name . "] is not exists");
        }

        // Destroy requested post
        $option->delete();

        return $this->response($option);

    }


}
