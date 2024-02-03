<li @if (Request::segment(2) == "options") class="active" @endif>
    <a href="{{ route("admin.options") }}">
        <span class="fa fa-cogs"></span>
    </a>
</li>
