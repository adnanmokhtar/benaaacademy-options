@if(count(config("admin.locales")) > 1)
    <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
            {{ strtoupper(Config::get("app.locale")) }}
        </a>
        <ul class="dropdown-menu dropdown-alerts dropdown-locales">
            <div class="aro"></div>
            @foreach (config("admin.locales") as $code => $lang)
                @if ($code != app()->getLocale())
                    <li>
                        <a href="{{ url("locale?lang=" . $code) }}">
                            {{ $lang["title"] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
@endif
