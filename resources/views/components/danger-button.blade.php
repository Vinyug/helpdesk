<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-red']) }}>
    {{ $slot }}
</button>
