<div class="bg-custom-light-blue p-3">
    <div class="container flex mx-auto text-white text-xs sm:text-base px-4 sm:px-6 lg:px-8">
        <div class="hidden md:flex items-center">
            <img src="{{ asset('assets/images/phone.svg') }}" alt="phone" class="w-4 h-4 mr-2">
            <p class="pr-4">0681875203</p>   

            <img src="{{ asset('assets/images/email.svg') }}" alt="email" class="w-4 h-4 ml-5 mr-2">
            <p>contact@axess-informatique.com</p> 
        </div>

        @auth
        <div class="md:ml-auto">
            Bonjour {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
        </div>
        @endauth
    </div>
</div>