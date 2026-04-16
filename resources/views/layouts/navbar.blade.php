<nav>
    <ul>
        @auth
            <li><a href="{{ route('home') }}" @if (Route::currentRouteName() === 'home') class="active" @endif>Dashboard</a></li>
            <li><a href="{{ route('books.index') }}" @if (str_contains(Route::currentRouteName(), 'books')) class="active" @endif>Books</a></li>
            <li><a href="{{ route('posts.index') }}" @if (str_contains(Route::currentRouteName(), 'posts')) class="active" @endif>Posts</a></li>

            @if (auth()->user()->role === 'admin')
                <li><a href="{{ route('users.index') }}" @if (str_contains(Route::currentRouteName(), 'users')) class="active" @endif>Users</a></li>
                <li><a href="{{ route('courses.index') }}" @if (str_contains(Route::currentRouteName(), 'courses')) class="active" @endif>Courses</a></li>
            @endif

            <li style="margin-left: auto;">
                <span style="color: white; padding: 0.5rem 1rem;">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}" @if (Route::currentRouteName() === 'login') class="active" @endif>Login</a></li>
            <li><a href="{{ route('register') }}" @if (Route::currentRouteName() === 'register') class="active" @endif>Register</a></li>
        @endauth
    </ul>
</nav>
