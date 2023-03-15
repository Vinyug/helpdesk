{{-- <nav class="container mx-auto p-3">
    <div class="flex items-center">
        <a class="order-3 sm:order-1 mx-auto sm:mr-6 sm:ml-0" href="#"><img class="logo-navbar h-auto w-10" src="{{ asset('assets/images/logo-axi.jpg')}}" alt="logo AXI"></a>
        <div class="order-4 sm:order-2 font-share-tech font-bold tracking-wide text-lg md:text-2xl text-custom-dark-blue">Helpdesk</div>

        <button class="order-1 sm:order-3 navbar-toggler sm:hidden" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <svg class="w-6 h-6 text-custom-dark-blue" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M2 5a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1zm16 6a1 1 0 01-1 1H3a1 1 0 110-2h14a1 1 0 011 1zm-1 4a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
            </svg>
        </button>

        <div class="order-2 sm:order-4 menu hidden sm:block sm:ml-auto sm:mr-3">
            <ul class="flex items-center space-x-4 font-bold">
                <li class="nav-item">
                    <a class="px-3 py-2 text-custom-light-blue hover:text-opacity-80 transition duration-300 ease-in-out" href="#">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 text-custom-light-blue hover:text-opacity-80 transition duration-300 ease-in-out" href="#">Inscription</a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 text-custom-light-blue hover:text-opacity-80 transition duration-300 ease-in-out" href="#">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 text-custom-light-blue hover:text-opacity-80 transition duration-300 ease-in-out" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 text-custom-light-blue hover:text-opacity-80 transition duration-300 ease-in-out" href="#">Menu</a>
                </li>
            </ul>
        </div>
    </div>
</nav> --}}



<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <a class="shrink-0 flex items-center order-3 sm:order-1 mx-auto sm:mr-4 sm:ml-0" href="#">
                <img class="logo-navbar" src="{{ asset('assets/images/logo-axi.jpg')}}" alt="logo AXI">
            </a>
            <div class="shrink-0 flex items-center order-4 sm:order-2 font-share-tech font-bold tracking-wide text-lg md:text-2xl text-custom-dark-blue">Helpdesk</div>

            <!-- Navigation Links -->
            <div class="hidden order-1 sm:order-3 space-x-8 sm:-my-px sm:ml-auto sm:pl-10 sm:flex">
                <ul>
                    <li class="inline-flex items-center ml-10 h-full">
                        <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="{{ route('dashboard') }}">Dashboard Retirer plus tard</a>
                    </li>
                    <li class="inline-flex items-center ml-10 h-full">
                        <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="{{ url('test') }}">Accueil TEST</a>
                    </li>
                    <li class="inline-flex items-center ml-10 h-full">
                        <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="">Connexion</a>
                    </li>
                    <li class="inline-flex items-center ml-10 h-full">
                        <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="">Inscription</a>
                    </li>
                    <li class="inline-flex items-center ml-10 h-full">
                        <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="">Contact</a>
                    </li>
                    <li class="inline-flex items-center ml-10 h-full">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            
                            <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Déconnexion</a>
                        </form>
                    </li>
                </ul>
            </div>
            
            <!-- Settings Dropdown -->
            <div class="hidden order-2 sm:order-4 sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 text-sm text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80">
                            <div>Menu</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <ul>
                            <li class="inline-flex items-center ml-4 my-1 w-full">
                                <a class="text-sm font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" href="{{ route('profile.edit') }}">Profile</a>
                            </li>
                        </ul>

                    </x-slot>
                </x-dropdown>
            </div>


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-custom-dark-blue hover:text-custom-light-blue transition duration-300 ease-in-out">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <ul>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="{{ route('dashboard') }}">Dashboard Retirer plus tard</a>
                </li>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="{{ url('test') }}">Accueil</a>
                </li>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="">Connexion</a>
                </li>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="">Inscription</a>
                </li>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="">Contact</a>
                </li>
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        
                        <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Déconnexion</a>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <li class="block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3">
                    <a class="text-base font-medium leading-5 text-custom-grey hover:text-custom-light-blue transition duration-300 ease-in-out" href="{{ route('profile.edit') }}">Profile</a>
                </li>
            </div>
        </div>
    </div>
</nav>