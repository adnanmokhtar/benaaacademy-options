@extends("admin::layouts.master")

@section("content")

    <form method="post">

        <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-3 col-md-3 col-sm-4 hidden-xs">
                <h2>
                    <i class="fa fa-cogs"></i>
                    {{ trans("options::options.options") }}
                </h2>
                <ol class="breadcrumb ">
                    <li>
                        <a href="{{ route("admin.options") }}">{{ trans("options::options.options") }}</a>
                    </li>
                    <li class="active">
                        <strong>{{ $option_page->title }}</strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">

                <ul class="nav nav-tabs option-tabs">

                    @foreach($option_pages as $page)

                        <li @if ($option_page->name == $page->name) class="active" @endif>
                            <a href="{{ admin_url("options/". $page->name) }}">
                                <i class="fa {{ $page->icon }}"></i>
                                <span class="hidden-sm hidden-xs hidden-md">{{ $page->title }}</span>
                            </a>
                        </li>

                    @endforeach

                </ul>

            </div>
        </div>

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="row">

                <div class="col-md-12">
                    <div class="panel ">
                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="options_main" class="tab-pane active">
                                    @foreach($option_page->views as $view => $payload)
                                        @include($view, $payload)
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div style="clear:both"></div>

            <div>
                <div class="container-fluid">
                    <div class="form-group">
                        <input type="submit" class="pull-left btn btn-flat btn-primary"
                               value="{{ trans("options::options.save_options") }}"/>
                    </div>
                </div>
            </div>

        </div>

    </form>

@stop
