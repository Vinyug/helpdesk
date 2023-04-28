<div class="container mb-16 mx-auto px-4">  
    <div class="relative mt-24 md:mt-36">

        {{--------------------- HEADER ----------------------}}
        <div class="text-center">
            <h2 class="font-share-tech text-3xl sm:text-4xl md:text-5xl font-medium">Nos Services</h2>
            <span class="absolute w-full -top-7 sm:-top-9 md:-top-16 left-0 font-share-tech uppercase text-custom-grey opacity-[4%] tracking-wide text-5xl sm:text-6xl md:text-8xl">Services</span>
        </div>
        
        {{--------------------- CARD ----------------------}}
        <div class="flex flex-col md:flex-row md:flex-wrap md:justify-center mt-16 md:mt-20">
            @php
                $counter = 0
            @endphp

            @foreach ($services as $service => $description)
                @if ($counter % 2 == 0)
                    {{-- card left --}}
                    <div class="w-full lg:w-2/5 xl:w-1/4 flex flex-col items-start mb-8 md:mb-12 md:mx-12 bg-white border border-orange-200 rounded-sm shadow">
                        <img class="self-center w-16 mt-8" style="filter: invert(52%) sepia(50%) saturate(756%) brightness(101%) contrast(90%);" src="assets/images/img-service.png" alt="image service">
                        <div class="flex flex-col justify-center my-2 p-8 leading-normal md:mr-2">
                            <h3 class="font-share-tech mb-2 text-2xl font-medium">{{ $service }}</h3>
                            <p class="mt-4">{!! nl2br(e($description)) !!}</p>
                        </div>
                    </div>
                @else
                    {{-- card right --}}
                    <div class="w-full lg:w-2/5 xl:w-1/4 flex flex-col items-start mb-8 md:mb-12 md:mx-12 bg-custom-light-blue text-white border border-gray-300 rounded-sm shadow shadow-gray-300">
                        <img class="self-center w-16 mt-8" style="filter: brightness(0) invert(1);" src="assets/images/img-service.png" alt="image service">
                        <div class="w-full flex flex-col justify-center my-2 p-8 leading-normal">
                            <h3 class="font-share-tech mb-2 text-2xl font-medium">{{ $service }}</h3>
                            <p class="mt-4">{!! nl2br(e($description)) !!}</p>
                        </div>
                    </div>
                @endif

                @php
                    $counter++;
                @endphp
            @endforeach
        </div>

    </div>
</div>
