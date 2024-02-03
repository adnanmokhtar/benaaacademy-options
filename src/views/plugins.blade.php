@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        @include("options::partials.nav")

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="row">
                    <div class="col-md-12">

                        @if (count($all_plugins))

                            @foreach ($all_plugins as $plugin)

                                <div class="panel panel-plugin">
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs text-center">
                                                <i class="plugin-icon fa {{ $plugin->icon }}"></i>
                                            </div>

                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <label
                                                    for="{{ $plugin->path }}_status">{{ ucfirst($plugin->name) }}

                                                </label>

                                                @if($plugin->description)
                                                    <p>
                                                        <small>{{ $plugin->description }}</small>
                                                    </p>
                                                @endif

                                                <p class="plugin-control">

                                                    @if ($plugin->installed)
                                                        <a href="{{ route("admin.plugins.activation", ["name" => $plugin->path, "status" => 0]) }}"
                                                           message="{{ trans("options::options.sure_uninstall_plugin", ["name" => $plugin->name]) }}"
                                                           class="text-danger ask">{{ trans("options::options.uninstall") }}</a>
                                                    @else
                                                        <a href="{{ route("admin.plugins.activation", ["name" => $plugin->path, "status" => 1]) }}"
                                                           class="text-navy ask"
                                                           message="{{ trans("options::options.sure_install_plugin", ["name" => $plugin->path]) }}">{{ trans("options::options.install") }}</a>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs text-right">
                                                <div class="plugin-version">{{ $plugin->version }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        @else

                            <div class="panel">
                                <div class="panel-body">
                                    <div>
                                        <i class="fa fa-puzzle-piece"></i>
                                        &nbsp; {{ trans("options::options.no_plugins") }}
                                    </div>
                                </div>
                            </div>

                        @endif

                    </div>

                </div>

        </div>
    </form>

    @section("head")

        <style>
            .plugin-icon {
                font-size: 56px;
                opacity: 0.3;
            }

            .plugin-version {
                font-size: 14px;
                opacity: 0.4;
                font-family: verdana !important;
            }

            .panel-plugin {
                border-radius: 5px;
                border: 1px solid #ddd;
                margin-bottom: 5px;
            }
        </style>

    @stop
    @section("footer")

        <script>
            $(document).ready(function () {
                var elems = Array.prototype.slice.call(document.querySelectorAll('.switcher'));
                elems.forEach(function (html) {
                    var switchery = new Switchery(html);
                });
            });
        </script>
    @stop
@stop
