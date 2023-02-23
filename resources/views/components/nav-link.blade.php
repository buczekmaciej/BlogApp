<a @if (Route::is($link['routeGroup'])) class="active-link" @endif
   href="{{ route($link['route']) }}">{{ $link['routeName'] }}</a>
