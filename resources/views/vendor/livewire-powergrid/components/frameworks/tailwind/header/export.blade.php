<div x-data="{ open: false }"
     @click.outside="open = false">
    <button @click.prevent="open = ! open"
            class="block bg-slate-50 text-custom-dark border-[2px] border-slate-300 rounded py-1.5 px-3 leading-tight
                   focus:outline-none focus:bg-white  focus:border-custom-blue transition duration-300 ease-in-out">
        <div class="flex">
            <x-livewire-powergrid::icons.download class="h-5 w-5 text-custom-dark-blue"/>
        </div>
    </button>

    <div x-show="open"
         x-cloak
         x-transition:enter="transform duration-200"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transform duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="mt-2 w-auto bg-white shadow-xl absolute z-10 border border-slate-300">

        @if(in_array('excel', data_get($setUp, 'exportable.type')))
            <div class="flex px-4 py-2 text-custom-grey border-slate-300">
                <span class="w-12">@lang('Excel')</span>
                <a x-on:click="$wire.call('exportToXLS'); open = false"
                   href="#"
                   class="px-2 block text-custom-dark hover:bg-slate-50 hover:text-black-300 rounded">
                    @lang('livewire-powergrid::datatable.labels.all')
                </a>
                @if($checkbox)
                    <a x-on:click="$wire.call('exportToXLS', true); open = false"
                       href="#"
                       class="px-2 block text-slate-800 hover:bg-slate-50 hover:text-black-300 dark:text-slate-200 dark:hover:bg-slate-700 rounded">
                        @lang('livewire-powergrid::datatable.labels.selected')
                    </a>
                @endif
            </div>
        @endif
        @if(in_array('csv', data_get($setUp, 'exportable.type')))
            <div class="flex px-4 py-2 text-custom-grey dark:text-slate-300">
                <span class="w-12">@lang('Csv')</span>
                <a x-on:click="$wire.call('exportToCsv'); open = false" href="#"
                   class="px-2 block text-custom-dark hover:bg-slate-50 hover:text-black-300 rounded">
                    @lang('livewire-powergrid::datatable.labels.all')
                </a>
                @if($checkbox)
                    <a x-on:click="$wire.call('exportToCsv', true); open = false" href="#"
                       class="px-2 block text-slate-800 hover:bg-slate-50 hover:text-black-300 dark:text-slate-200 dark:hover:bg-slate-700 rounded">
                        @lang('livewire-powergrid::datatable.labels.selected')
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
