<div id="contactForm" class="container mb-16 mx-auto px-4">  
    <div class="relative mt-24 md:mt-36 mb-8 md:mb-16">

        {{--------------------- HEADER ----------------------}}
        <div class="text-center">
            <h2 class="font-share-tech text-3xl sm:text-4xl md:text-5xl font-medium">Contactez-nous</h2>
            <span class="absolute w-full -top-7 sm:-top-9 md:-top-16 left-0 font-share-tech uppercase text-custom-grey opacity-[3%] tracking-wide text-5xl sm:text-6xl md:text-8xl">Contact</span>
        </div>

        <div class="flex flex-col md:flex-row justify-start md:justify-center gap-8">
            <form class="mt-8" action="" method="POST">
                <div class="grid sm:grid-cols-2 gap-4 p-4">    
                    <div class="w-full">
                        <label for="fullname" class="custom-label">Prénom / NOM : <span class="text-custom-blue font-bold">*</span></label>
                        <input type="text" name="fullname" id="fullname" class="custom-input" placeholder="Saisir votre nom complet" required>
                        @error('fullname')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <label for="email" class="custom-label">Email : <span class="text-custom-blue font-bold">*</span></label>
                        <input type="email" name="email" id="email" class="custom-input" placeholder="Saisir l'email" required>
                        @error('email')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <label for="address" class="custom-label">Adresse : </label>
                        <input type="text" name="address" id="address" class="custom-input" placeholder="Saisir l'adresse">
                        @error('address')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="w-full">
                        <label for="company" class="custom-label">Entreprise : </label>
                        <input type="text" name="company" id="company" class="custom-input" placeholder="Saisir l'entreprise">
                        @error('company')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="col-span-full">
                        <label for="content" class="custom-label">Message : <span class="text-custom-blue font-bold">*</span></label>
                        <textarea class="custom-input h-40" name="content" id="content" placeholder="Saisir un message"></textarea>
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