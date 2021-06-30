@php
use Larabase\Nova\Support\MenuHelper;
$sidebarTools = MenuHelper::getSidebarTools()
@endphp
<!-- Sidebar -->
<div class="sidebar-header min-h-screen flex flex-col pt-header min-h-screen w-sidebar bg-grad-sidebar">
    <a href="{{ \Laravel\Nova\Nova::path() }}">
        <div class="pin-t pin-l pin-r bg-logo flex items-center w-sidebar h-header px-6 text-white">
            @include('nova::partials.logo')
        </div>
    </a>

    <div class="sidebar-content">
        <div class="sidebar-scroll px-6 pt-6">
            @foreach ($sidebarTools as $tool)
                {!! $tool->renderNavigation() !!}
            @endforeach
        </div>
    </div>
</div>
