@if(data_get($setUp, 'header.searchInput'))
    <div class="flex flex-row mt-2 md:mt-0 w-full rounded-full justify-start sm:justify-center md:justify-end">
        <div class="relative rounded-full w-full float-end float-right">
              <span class="absolute inset-y-0 left-0 flex items-center pl-1">
                 <span class="p-1 focus:outline-none focus:shadow-outline">
                    <x-livewire-powergrid::icons.search class="text-slate-300 dark:text-slate-200"/>
                 </span>
              </span>
            <input wire:model.debounce.600ms="search" type="text"
                   style="padding-left: 36px !important;"
                   class="custom-input text-sm py-1 px-2"
                   placeholder="{{ trans('livewire-powergrid::datatable.placeholders.search') }}">
            @if($search)
                <span class="absolute inset-y-0 right-0 flex items-center pl-1">
                    <span class="p-1 focus:outline-none focus:shadow-outline cursor-pointer">
                        <a wire:click.prevent="$set('search','')">
                            <x-livewire-powergrid::icons.x class="text-slate-300 mr-2 w-5 h-5 dark:text-slate-200"/>
                        </a>
                    </span>
                </span>
            @endif
        </div>
    </div>
@endif
