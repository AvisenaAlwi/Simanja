@auth()
    @include('layouts.navbars.navs.auth')
@endauth
    
@guest()
    @include('layouts.navbars.navs.guest', ['showSearch' => $showSearch])
@endguest