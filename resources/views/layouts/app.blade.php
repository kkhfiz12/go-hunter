@props(['title' => null, 'description' => null, 'sidebarContext' => null, 'hideFooter' => false])

<x-layouts.app
    :title="$title"
    :description="$description"
    :sidebarContext="$sidebarContext"
    :hideFooter="$hideFooter"
>
    {{ $slot ?? '' }}
    @yield('content')
</x-layouts.app>
