<div class=" bg-gradient-to-br from-blue-900 to-indigo-900">
    <div class="container mb-16 mx-auto px-4">  
        <div class="relative w-full my-32 md:my-44">
        
            {{--------------------- HEADER ----------------------}}
            <div class="text-center">
                <h2 class="font-share-tech text-white text-3xl sm:text-4xl md:text-5xl font-medium">Nos clients nous font confiance</h2>
                <span class="absolute text-white w-full -top-7 sm:-top-9 md:-top-16 left-0 font-share-tech uppercase opacity-[3%] tracking-wide text-5xl sm:text-6xl md:text-8xl">Expériences</span>
            </div>
            
            @if($reviews->count() > 0)
                {{--------------------- CARD ----------------------}}
                <div class="flex flex-wrap justify-center mt-16 md:mt-20">
                    @foreach ($reviews as $review)
                        <div class="flex flex-col items-start w-full h-full md:w-2/5 xl:w-[28%] m-8 p-8 bg-white border border-gray-200 rounded-sm shadow">
                            <div class="flex items-center justify-center">
                                <div class="flex rating mb-4 space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <img class="star-full no-hover w-4" 
                                            src="{{ ($i <= $review->rate) ? asset('assets/images/star-full.png') : asset('assets/images/star-empty.png') }}" 
                                            alt="{{ $i }} étoile(s)">
                                    @endfor
                                </div>
                            </div>
                            <p class="text-custom-grey font-medium italic">{{ $review->created_at->format('d.m.Y') }}</p>
                            <p class="my-8">{!! nl2br(e($review->content)) !!}</p>
                            <p class="mt-auto font-bold">{{ $review->user->firstname }} {{ substr($review->user->lastname, 0, 1) . '.' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center mt-24 text-white text-xl sm:text-2xl md:text-3xl font-medium">Nous sommes en cours de collecte d'avis, veuillez revenir ultérieurement.</p>
            @endif
        </div>
    </div>
</div>