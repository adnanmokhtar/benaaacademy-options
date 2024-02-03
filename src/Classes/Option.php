<?php

namespace Benaaacademy\Options\Classes;

use Benaaacademy\Options\Models\Option as OptionModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

/**
 * Class Option
 * @package Benaaacademy\Platform\Classes
 */
class Option
{

    /**
     * Option pages
     * @var array
     */
    public static $pages = [];

    /**
     * List of option pages
     * @var array
     */
    public static $all = [];

    /**
     * The option page object
     * @var object
     */
    public static $page;

    /**
     * System options
     * @var array
     */
    public static $options;


    /**
     * Option constructor.
     */
    function __construct($app)
    {

        $this->app = $app;

        // Load all system options from database

        try {

            if (is_null(self::$options)) {

                self::$options = new Collection();

                $options = OptionModel::all();

                if (count($options)) {
                    self::$options = OptionModel::all();
                }

            }

        } catch (QueryException $exception) {

            // Skip all errors before platform install in console environment

            if (!app()->runningInConsole()) {
                return response("<b>Benaaacademy Platform</b> is not installed.<br/> please install using the artisan command:
                    <br/> <pre>$ php artisan Benaaacademy:install</pre>")->send();
            }

        }
    }


    /**
     * Get all options
     * @return bool
     */
    public function all()
    {
        return self::$options;
    }

    /**
     * Check option name is exists
     * @param $name
     * @return bool
     */
    public function has($name)
    {

        $option = $option = self::$options->where("name", $name)
            ->whereIn("lang", [$this->app->getLocale(), NULL])->count();

        if ($option) {
            return true;
        }

        return false;
    }

    /**
     * Get option value by name
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function get($name, $default = NULL)
    {

        $option = $option = self::$options->where("name", $name)
            ->whereIn("lang", [$this->app->getLocale(), NULL])->first();

        if ($option) {
            return $option->value;
        }

        return $default;
    }

    /**
     * Create or update options
     * @param $name
     * @param null $value
     * @param int $isLocalized
     */
    public function set($name, $value = NULL, $isLocalized = 0)
    {

        if ($option = self::$options->where("name", $name)
            ->whereIn("lang", [NULL, $this->app->getLocale()])->first()) {

            $option->value = $value;

            $option->save();

            if ($option->is_localized) {

                // Option is exist as a localized option
                // We will update its value

                self::$options = self::$options->map(function ($option) use ($name, $value) {

                    if ($option->name == $name and $option->lang == $this->app->getLocale()) {
                        $option->value = $value;
                    }

                    return $option;
                });


            } else {

                // Option is exist as a non localized option
                // We will update its value

                self::$options = self::$options->map(function ($option) use ($name, $value) {

                    if ($option->name == $name and $option->lang == NULL) {
                        $option->value = $value;
                    }

                    return $option;
                });

            }

        } elseif ($option = self::$options->where("name", $name)
            ->whereNotIn("lang", [NULL, $this->app->getLocale()])->first()) {

            // Option is not exist in current locale
            // We will add add it to current locale

            $this->add($name, $value, $option->is_localized);

        } else {


            // Option is not exist any more
            // We will add it

            $this->add($name, $value, $isLocalized);

        }

    }

    /**
     * Add new option
     * @param $name
     * @param null $value
     * @param int $isLocalized
     */
    public function add($name, $value = NULL, $isLocalized = 0)
    {

        $option = new OptionModel();

        $option->name = $name;
        $option->value = $value;
        $option->lang = $isLocalized ? $this->app->getLocale() : NULL;

        $option->save();

        self::$options->push($option);
    }

    /**
     * Delete option by name
     * @param $name
     */
    public function delete($name)
    {

        self::$options = self::$options->reject(function ($option) use ($name) {
            return $option->name == $name;
        });

        // Sync changes with database
        // Deleting the default and localized options

        OptionModel::where("name", $name)->delete();

    }

    public function page($name, $callback = false)
    {

        if (!in_array($name, self::$all)) {
            self::$all[] = $name;
        }

        Event::listen($name . '.options', function () use ($name, $callback) {

            self::$page = new self($this->app);

            self::$page->name = $name;
            self::$page->title = "";
            self::$page->order = 0;
            self::$page->icon = "fa-sliders";
            self::$page->permission = NULL;
            self::$page->views = [];

            call_user_func_array($callback, [self::$page]);

            self::$pages[$name] = self::$page;
        });

    }

    public function getPage($name)
    {
        return array_key_exists($name, self::$pages) ? self::$pages[$name] : false;
    }


    public function pages()
    {

        foreach (self::$all as $name) {
            Event::dispatch($name . ".options");
        }

        if (count(self::$pages)) {
            self::$pages = collect(self::$pages)->where("views", "!=", [])->toArray();
        }

        return self::$pages;
    }

    /**
     * Set name of page
     * @param string $name
     * @return mixed
     */
    function name($name)
    {
        self::$page->name = $name;
        return self::$page;
    }

    /**
     * Set title of page
     * @param string $title
     * @return mixed
     */
    function title($title = "")
    {
        self::$page->title = $title;
        return self::$page;
    }

    /**
     * Set icon of page
     * @param string $icon
     * @return mixed
     */
    function icon($icon = "")
    {
        self::$page->icon = $icon;
        return self::$page;
    }

    /**
     * Set page order
     * @param int $order
     * @return mixed
     */
    function order($order = 0)
    {
        self::$page->order = $order;
        return self::$page;
    }

    /**
     * Set page permission
     * @param string $permission
     * @return mixed
     */
    function permission($permission = NULL)
    {
        self::$page->permission = $permission;
        return self::$page;
    }

    /**
     * Set page view
     * @param $view
     * @param array $payload
     * @return mixed
     */
    function view($view, $payload = [])
    {
        self::$page->views[$view] = $payload;
        return self::$page;
    }

}
