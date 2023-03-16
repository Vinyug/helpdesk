
<div class="h-auto flex flex-col sm:justify-center items-center my-auto pt-6 sm:pt-0 pb-8">
    <div class="mt-8">
        <a href="/">
            <x-application-logo/>
        </a>
    </div>

    <div class="w-full border-[1px] border-slate-50 sm:max-w-md mt-12 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

