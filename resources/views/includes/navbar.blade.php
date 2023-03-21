<nav x-data="{ open: false }" class="z-10 sticky top-0 bg-white border-b-[1px] border-custom-dark border-opacity-30 shadow-sm shadow-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="z-10 container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <a class="shrink-0 flex items-center order-3 sm:order-1 mx-auto sm:mr-4 sm:ml-0" href="/">
                <x-application-logo/>
            </a>
            <div class="shrink-0 flex items-center order-4 sm:order-2 font-share-tech font-bold tracking-wide text-lg md:text-2xl text-custom-dark-blue">Helpdesk</div>

            <!-- Navigation Links -->
            <div class="hidden order-1 sm:order-3 space-x-8 sm:-my-px sm:ml-auto sm:pl-10 sm:flex">
                <x-nav-link :href="url('/')" :active="Request::url() == url('/')">
                    {{ __('Accueil') }}
                </x-nav-link>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Contact') }}
                </x-nav-link>

                @guest
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Connexion') }}
                    </x-nav-link>
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Inscription') }}
                    </x-nav-link>   
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}" style="margin-left: 0;">
                        @csrf

                        <x-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Déconnexion') }}
                        </x-nav-link>
                    </form>

                    <x-nav-link @click="isOpen = !isOpen">
                        {{ __('Menu') }}
                    </x-nav-link>
                @endauth
            </div>
            

            <!-- Sidebar -->
            <div class="hidden sm:block absolute transform transition duration-700 top-0 -right-80 py-4 z-10 w-80 bg-white h-[calc(100vh-65px)] mt-[65px] border-r-[1px] border-custom-dark border-opacity-30 shadow-md shadow-gray-400" :class="{'opacity-0':isOpen === false, '-translate-x-full opacity-100': isOpen === true}">

                <div class="flex justify-between">
                    <h2 class="font-bold pl-4 text-custom-dark text-2xl sm:text-3xl">Menu</h2>
                    <button class="p-2 rounded text-custom-dark hover:text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80" @click="isOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 rotate-0 hover:-rotate-180 transition duration-[400ms] ease-in-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                <!-- Navigation -->
                </div>
                    <x-dropleft-link :href="route('profile.edit')">
                        {{ __('Mes informations') }}
                    </x-dropleft-link>
                    
                    @can('company-list')
                    <x-dropleft-link :href="route('companies.index')">
                        {{ __('Liste des entreprises') }}
                    </x-dropleft-link>
                    @endcan

                    @can('user-list')
                    <x-dropleft-link :href="route('users.index')">
                        {{ __('Liste des utilisateurs') }}
                    </x-dropleft-link>
                    @endcan

                    @can('role-list')
                    <x-dropleft-link :href="route('roles.index')">
                        {{ __('Roles') }}
                    </x-dropleft-link>
                    @endcan

                    @can('job-list')
                    <x-dropleft-link :href="route('jobs.index')">
                        {{ __('Postes') }}
                    </x-dropleft-link>
                    @endcan

                    @can('state-list')
                    <x-dropleft-link :href="route('states.index')">
                        {{ __('États') }}
                    </x-dropleft-link>
                    @endcan

                    @can('service-list')
                    <x-dropleft-link :href="route('services.index')">
                        {{ __('Services') }}
                    </x-dropleft-link>
                    @endcan
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
            <x-responsive-nav-link :href="url('/')" :active="request()->routeIs('index')">
                {{ __('Accueil') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Contact') }}
            </x-responsive-nav-link>

            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Connexion') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    {{ __('Inscription') }}
                </x-responsive-nav-link>   
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" style="margin-left: 0;">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Déconnexion') }}
                    </x-responsive-nav-link>
                </form>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">Nom</div>
                <div class="font-medium text-sm text-gray-500">Email</div>
            </div>

            <h2 class="my-3 font-bold text-xl pl-4">Menu</h2>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Mes informations') }}
                </x-responsive-nav-link>
                
                @can('company-list')
                <x-responsive-nav-link :href="route('companies.index')">
                    {{ __('Listes des entreprises') }}
                </x-responsive-nav-link>
                @endcan

                @can('user-list')
                <x-responsive-nav-link :href="route('users.index')">
                    {{ __('Listes des utilisateurs') }}
                </x-responsive-nav-link>
                @endcan

                @can('role-list')
                <x-responsive-nav-link :href="route('roles.index')">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
                @endcan

                @can('job-list')
                <x-responsive-nav-link :href="route('jobs.index')">
                    {{ __('Postes') }}
                </x-responsive-nav-link>
                @endcan

                @can('state-list')
                <x-responsive-nav-link :href="route('states.index')">
                    {{ __('États') }}
                </x-responsive-nav-link>
                @endcan

                @can('service-list')
                <x-responsive-nav-link :href="route('services.index')">
                    {{ __('Services') }}
                </x-responsive-nav-link>
                @endcan
            </div>
        </div>
    </div>
</nav>