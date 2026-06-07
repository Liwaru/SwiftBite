<header class="topbar">
    <a class="brand" href="{{ route('baker.dashboard') }}" aria-label="Buka dashboard baker">
        <span class="logo">
            <img src="{{ asset('images/Swiftbite.png') }}" alt="SwiftBite">
        </span>
        <div>
            <strong>SwiftBite</strong>
            <span>Baker</span>
        </div>
    </a>

    <form class="logout" method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>
