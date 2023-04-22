<div class="relative mt-24 md:mt-36">

    {{--------------------- HEADER ----------------------}}
    <div class="text-center">
        <h2 class="font-share-tech text-3xl sm:text-4xl md:text-5xl font-medium">Nos Services</h2>
        <span class="absolute w-full -top-7 sm:-top-9 md:-top-16 left-0 font-share-tech uppercase text-custom-grey opacity-[4%] tracking-wide text-5xl sm:text-6xl md:text-8xl">Services</span>
    </div>
    
    {{--------------------- CARD ----------------------}}
    <div class="flex flex-col mt-16 md:mt-20">
        @php
            $counter = 0
        @endphp

        @foreach ($services as $service => $description)
            @if ($counter % 2 == 0)
                {{-- card left --}}
                <div class="flex flex-col md:self-start items-center mb-8 md:mb-12 bg-white border border-gray-200 rounded-sm shadow md:flex-row md:max-w-xl xl:max-w-3xl md:min-h-[256px] md:ml-[7%] lg:ml-[15%] 2xl:ml-[18%]">
                    <img class="md:order-1 w-16 mt-8 md:mt-0 md:ml-10 md:mr-4" src="assets/images/img-service.png" alt="image service">
                    <div class="md:order-2 flex flex-col justify-between my-2 p-8 leading-normal md:mr-2">
                        <h3 class="font-share-tech mb-2 text-2xl font-medium">{{ $service }}</h3>
                        <p class="mt-4">{{ $description }}</p>
                    </div>
                </div>
            @else
                {{-- card right --}}
                <div class="flex flex-col md:self-end items-center mb-8 md:mb-12 bg-custom-light-blue text-white border border-gray-300 rounded-sm shadow shadow-gray-300 md:flex-row md:max-w-xl xl:max-w-3xl md:min-h-[256px] md:mr-[7%] lg:mr-[15%] 2xl:mr-[18%]">
                    <img class="md:order-2 w-16 mt-8 md:mt-0 md:mr-10 md:ml-4" style="filter: brightness(0) invert(1);" src="assets/images/img-service.png" alt="image service">
                    <div class="md:order-1 flex flex-col justify-between my-2 p-8 leading-normal md:ml-2">
                        <h3 class="font-share-tech mb-2 text-2xl font-medium">{{ $service }}</h3>
                        <p class="mt-4">{{ $description }}</p>
                    </div>
                </div>
            @endif

            @php
                $counter++;
            @endphp
        @endforeach
    </div>

</div>
