<?php

namespace App\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Tailwind;
use PowerComponents\LivewirePowerGrid\Themes\Theme;
use PowerComponents\LivewirePowerGrid\Themes\Components\{Actions,
    Checkbox,
    ClickToCopy,
    Cols,
    Editable,
    FilterBoolean,
    FilterDatePicker,
    FilterInputText,
    FilterMultiSelect,
    FilterNumber,
    FilterSelect,
    Footer,
    Table};

class CustomTheme extends Tailwind
{
    public string $name = 'tailwind';

    public function table(): Table
    {
        return Theme::table('min-w-full border border-slate-300 text-sm')
            ->div('my-3 overflow-x-auto overflow-y-auto bg-white shadow-md rounded-sm relative')
            ->thead('shadow-sm bg-custom-light-blue border border-slate-300')
            ->tr('')
            ->trFilters('bg-custom-light-blue shadow-sm')
            ->th('font-semibold px-2 pr-4 py-3 text-left text-white tracking-wider whitespace-nowrap')
            ->tbody('text-custom-dark')
            ->trBody('border border-slate-300 hover:bg-sky-50')
            ->tdBody('px-3 py-2 whitespace-nowrap')
            ->tdBodyTotalColumns('px-3 py-2 whitespace-nowrap text-custom-dark text-right space-y-2');
    }

    public function footer(): Footer
    {
        return Theme::footer()
            ->view($this->root() . '.footer')
            ->select('block appearance-none bg-slate-50 border border-slate-300 text-slate-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-slate-500');
    }

    public function actions(): Actions
    {
        return Theme::actions()
            ->headerBtn('block w-full bg-slate-300 text-slate-700 border border-slate-200 rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white focus:border-slate-600')
            ->rowsBtn('focus:outline-none text-sm py-2.5 px-5 rounded border');
    }

    public function cols(): Cols
    {
        return Theme::cols()
            ->div('')
            ->clearFilter('', '');
    }

    public function editable(): Editable
    {
        return Theme::editable()
            ->view($this->root() . '.editable')
            ->span('flex justify-between')
            ->input('bg-slate-50 text-black-700 border border-slate-200 rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white focus:border-slate-200 shadow-md');
    }

    public function clickToCopy(): ClickToCopy
    {
        return Theme::clickToCopy()
            ->span('flex justify-between');
    }

    public function checkbox(): Checkbox
    {
        return Theme::checkbox()
            ->th('px-6 py-3 text-left text-xs font-medium text-slate-500 tracking-wider')
            ->label('flex items-center space-x-3')
            ->input('h-4 w-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out');
    }

    public function filterBoolean(): FilterBoolean
    {
        return Theme::filterBoolean()
            ->view($this->root() . '.filters.boolean')
            ->base('min-w-[5rem]')
            ->select('appearance-none block mt-1 mb-1 bg-white border border-slate-300 text-slate-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-slate-500 w-full active dark:bg-slate-600 dark:text-slate-200 dark:placeholder-slate-200 dark:border-slate-500', 'max-width: 370px');
    }

    public function filterDatePicker(): FilterDatePicker
    {
        return Theme::filterDatePicker()
            ->base('p-2')
            ->view($this->root() . '.filters.date-picker')
            ->input('flatpickr flatpickr-input custom-input text-sm py-1 px-2', 'min-width: 12rem');
    }

    public function filterMultiSelect(): FilterMultiSelect
    {
        return Theme::filterMultiSelect()
            ->base('inline-block relative w-full p-2 min-w-[180px]')
            ->view($this->root() . '.filters.multi-select');
    }

    public function filterNumber(): FilterNumber
    {
        return Theme::filterNumber()
            ->view($this->root() . '.filters.number')
            ->input('custom-input text-sm py-1 px-2 min-w-[5rem]');
    }

    public function filterSelect(): FilterSelect
    {
        return Theme::filterSelect()
            ->view($this->root() . '.filters.select')
            ->base('min-w-[9.5rem]')
            ->select('custom-input text-sm py-1 px-2 active');
    }

    public function filterInputText(): FilterInputText
    {
        return Theme::filterInputText()
            ->view($this->root() . '.filters.input-text')
            ->base('min-w-[9.5rem]')
            ->select('custom-input text-sm py-1 px-2 hidden')
            ->input('custom-input text-sm py-1 px-2');
    }
}
