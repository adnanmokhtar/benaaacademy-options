<?php

namespace Benaaacademy\Options\Controllers;

use Benaaacademy;
use Benaaacademy\Options\Facades\Option;
use Benaaacademy\Platform\Controller;
use Benaaacademy\Platform\Facades\Action;
use File;
use Gate;
use Redirect;
use Request;
use Session;
use View;

/**
 * Class OptionsController
 * @package Benaaacademy\Options\Controllers
 */
class OptionsController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * Render the option page
     * @param $page
     * @return mixed
     */
    function index($page = false)
    {

        if (!$page) return redirect()->route("admin.options", ["page" => "general"]);

        if (Request::isMethod("post")) {

            foreach (Request::get("option") as $name => $value) {

                // Fire saving action

                Action::fire("option.saving", $name, $value);

                Option::set($name, $value);

                // Fire saved action

                Action::fire("option.saved", $name, $value);
            }

            return redirect()->back()
                ->with("message", trans("options::options.events.saved", [], "messages", option("site_locale")));
        }

        $this->data["option_pages"] = Option::pages();
        $this->data["option_page"] = Option::getPage($page);

        return View::make("options::show", $this->data);
    }
}
