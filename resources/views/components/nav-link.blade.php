<a @if (Route::is($link['routeGroup'])) class="active-link" @endif
   href="{{ route($link['route'], implode(', ', $link['arguments'])) }}">{{ $link['routeName'] }}</a>
