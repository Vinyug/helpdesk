<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-blue']) }}>
    {{ $slot }}
</button>
