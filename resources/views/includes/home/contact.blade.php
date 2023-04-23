<div id="contactForm" class="container mb-16 mx-auto px-4">  
    <div class="relative mt-24 md:mt-36 mb-8 md:mb-16">

        {{--------------------- HEADER ----------------------}}
        <div class="text-center">
            <h2 class="font-share-tech text-3xl sm:text-4xl md:text-5xl font-medium">Contactez-nous</h2>
            <span class="absolute w-full -top-7 sm:-top-9 md:-top-16 left-0 font-share-tech uppercase text-custom-grey opacity-[3%] tracking-wide text-5xl sm:text-6xl md:text-8xl">Contact</span>
        </div>

        <div class="flex flex-col lg:flex-row justify-start md:justify-center gap-4">
            <form class="mt-8 w-full lg:w-2/3 xl:w-1/2 2xl:w-2/5" action="" method="POST">
                <div class="grid sm:grid-cols-2 gap-4 p-4">    
                    <div class="w-full">
                        <input type="text" name="fullname" id="fullname" class="custom-input-contact" placeholder="Prénom / NOM *" required>
                        @error('fullname')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <input type="email" name="email" id="email" class="custom-input-contact" placeholder="Email *" required>
                        @error('email')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <input type="text" name="address" id="address" class="custom-input-contact" placeholder="Adresse">
                        @error('address')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <input type="text" name="company" id="company" class="custom-input-contact" placeholder="Entreprise">
                        @error('company')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="col-span-full">
                        <textarea class="custom-input-contact h-40" name="content" id="content" placeholder="Message *"></textarea>
                        @error('content')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="block col-span-full text-custom-blue mb-5 ml-4">* les champs obligatoires</div>
    
                    <div class="col-span-full">
                        <button type="submit" class="btn-orange sm:w-auto px-8 sm:ml-auto sm:mr-0 uppercase tracking-wide">Envoyer</button>
                    </div>
                </div>
            </form>

            <div class="flex flex-col md:mt-8 p-4">
                <div class="flex">
                    <img src="{{ asset('assets/images/location.png') }}" alt="icône-localisation" class="object-none self-start">
                    <div class="flex flex-col ml-6 self-start">
                        <h4 class="font-share-tech font-medium text-xl sm:text-2xl mb-4">Bureau</h4>
                        <span class="font-medium text-sm sm:text-base mb-4">5 La Jarrige</span>
                        <span class="font-medium text-sm sm:text-base">23320 Saint-Vaury</span>
                    </div>
                </div>
                <div class="flex mt-12">
                    <img src="{{ asset('assets/images/clock.png') }}" alt="icône-horloge" class="object-none self-start">
                    <div class="flex flex-col ml-6 self-start">
                        <h4 class="font-share-tech font-medium text-xl sm:text-2xl mb-4">Horaire</h4>
                        <span class="font-medium text-sm sm:text-base">Sur RDV 7/7</span>
                    </div>
                </div>
                <div class="flex mt-8">
                    <img src="{{ asset('assets/images/phone.png') }}" alt="icône-téléphone" class="object-none self-start">
                    <div class="flex flex-col ml-6 self-start">
                        <h4 class="font-share-tech font-medium text-xl sm:text-2xl mb-4">Téléphone</h4>
                        <span class="font-medium text-sm sm:text-base">06.81.87.52.03</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>