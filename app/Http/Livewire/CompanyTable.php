<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\Rule;
use PowerComponents\LivewirePowerGrid\Rules\RuleActions;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;

final class CompanyTable extends PowerGridComponent
{
    use ActionButton;

    // Sort by default
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
    * PowerGrid datasource.
    *
    * @return Builder<\App\Models\Company>
    */
    public function datasource(): Builder
    {
        return Company::query();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            // ->addColumn('id')
            ->addColumn('name')

           /** Example of custom column using a closure **/
            ->addColumn('name_lower', function (Company $model) {
                return strtolower(e($model->name));
            })

            ->addColumn('address')
            ->addColumn('city')
            ->addColumn('zip_code')
            // ->addColumn('siret')
            // ->addColumn('code_ape')
            // ->addColumn('phone')
            ->addColumn('email')
            // ->addColumn('uuid')
            ->addColumn('active', fn(Company $company) => $company->active ? 'Activé' : 'Désactivé')
            ->addColumn('created_at_formatted', fn (Company $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            // ->addColumn('updated_at_formatted', fn (Company $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'))
            ;
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            // Column::make('ID', 'id')
            //     ->makeInputRange(),

            Column::make(trans('Name'), 'name')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Address'), 'address')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('City'), 'city')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('ZIP Code'), 'zip_code'),
                // ->makeInputRange(),

            // Column::make('SIRET', 'siret')
            //     ->makeInputRange(),

            // Column::make('CODE APE', 'code_ape')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make(trans('Phone'), 'phone')
            //     ->makeInputRange(),

            Column::make(trans('Email'), 'email')
                ->sortable()
                ->searchable()
                ->makeInputText(),
                
                // Column::make('UUID', 'uuid')
                //     ->sortable()
                //     ->searchable()
                //     ->makeInputText(),
                
            Column::make(trans('Account'), 'active')
                ->sortable(),
                // ->toggleable(),

            Column::make(trans('Created at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                // ->makeInputDatePicker(),

            // Column::make(trans('Updated at'), 'updated_at_formatted', 'updated_at')
            //     ->searchable()
            //     ->sortable()
            //     ->makeInputDatePicker(),

        ]
        ;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

     /**
     * PowerGrid Company Action Buttons.
     *
     * @return array<int, Button>
     */

    
    public function actions(): array
    {
        return [
        Button::make('show', trans(''))
            ->class('btn-show')
            ->target('')
            ->route('companies.show', ['company' => 'uuid']),
        
            Button::make('edit', trans(''))
            ->class('btn-edit')
            ->target('')
            ->route('companies.edit', ['company' => 'uuid']),
            
        Button::make('destroy', trans(''))
            ->class('btn-delete')
            ->openModal('delete-company', ['company' => 'uuid']),
        ];
    }
    

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

     /**
     * PowerGrid Company Action Rules.
     *
     * @return array<int, RuleActions>
     */

    
    public function actionRules(): array
    {
        return [

           //Hide action edit if user have not permission
            Rule::button('edit')
                ->when(fn() => auth()->user()->can('company-edit') === false)
                ->hide(),

           //Hide action delete if user have not permission
            Rule::button('destroy')
                ->when(fn() => auth()->user()->can('company-delete') === false)
                ->hide(),
        ];
    }
}
