<header class="topbar">
    <a class="brand" href="{{ route('chef.dashboard') }}" aria-label="Buka dashboard chef">
        <span class="logo">
            <img src="{{ asset('images/Swiftbite.png') }}" alt="SwiftBite">
        </span>
        <div>
            <strong>SwiftBite</strong>
            <span>Chef</span>
        </div>
    </a>

    <form class="logout" method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>
