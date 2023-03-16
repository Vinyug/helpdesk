<button {{ $attributes->merge(['type' => 'submit', 'class' => 'block w-full rounded-lg uppercase bg-custom-orange border-4 border-custom-orange px-5 py-3 text-sm font-medium text-white hover:text-custom-orange hover:scale-[1.02]  hover:bg-white hover:font-bold transition duration-300 ease-in-out']) }}>
    {{ $slot }}
</button>
