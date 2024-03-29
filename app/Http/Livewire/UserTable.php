<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

final class UserTable extends PowerGridComponent
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
    * @return Builder<\App\Models\User>
    */
    public function datasource(): Builder
    {
        // left join permits to take user datas with or without company
        $query = User::query()
            ->leftjoin('companies', 'users.company_id', '=', 'companies.id')
            ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select([
                'users.*',
                'users.email as user_email', // need to identify, cause field email exists in table company
                'companies.name as company_name',
                DB::raw('GROUP_CONCAT(DISTINCT roles.name SEPARATOR ", ") as role_name'),
            ])
            ->groupBy('users.id');

        // if user authenticate have all-access, can see every users of DB
        if (auth()->user()->can('all-access')) {
            return $query;
        } else {
            // else only users of his company
            return $query
                ->where('company_id', '=', auth()->user()->company_id);
        }
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
        return [
            'company' => [
                'name',
            ],
            'roles' => [
                'name',
            ],
        ];
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
            ->addColumn('company_id')
            ->addColumn('job')
            // custom column company of user
            ->addColumn('company_name', fn (User $user) => $user->company ? $user->company->name : '')
            // custom column roles of user
            ->addColumn('role_name', fn (User $user) => $user->roles->pluck('name')->implode(', '))
            ->addColumn('firstname')
            ->addColumn('lastname')
            ->addColumn('user_email')
            ->addColumn('active', fn (User $user) => $user->active ? 'Activé' : 'Désactivé')
            ->addColumn('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            // ->addColumn('updated_at_formatted', fn (User $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'))
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

            // Column::make('COMPANY ID', 'company_id')
            //     ->makeInputRange(),
           
            Column::make(trans('Firstname'), 'firstname')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Lastname'), 'lastname')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Email'), 'user_email', 'users.email')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Company'), 'company_name', 'companies.name')
                ->sortable()
                ->searchable()
                ->makeInputText(),
                
            Column::make(trans('Job'), 'job')
                ->sortable()
                ->searchable()
                ->makeInputText(),
        
            Column::make(trans('Role'), 'role_name', 'roles.name')
                ->sortable()
                ->searchable()
                ->makeInputText(),
            
            Column::make(trans('Account'), 'active')
                ->sortable(),

            Column::make(trans('Created at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                // ->makeInputDatePicker(),

            // Column::make('UPDATED AT', 'updated_at_formatted', 'updated_at')
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
     * PowerGrid User Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
           Button::make('show', trans(''))
                ->class('btn-show')
                ->target('')
                ->route('users.show', ['user' => 'id']),
                 
           Button::make('edit', trans(''))
                ->class('btn-edit')
                ->target('')
                ->route('users.edit', ['user' => 'id']),
                 
                Button::make('destroy', trans(''))
                ->class('btn-delete')
                ->openModal('delete-user', ['user' => 'id']),
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
     * PowerGrid User Action Rules.
     *
     * @return array<int, RuleActions>
     */

    public function actionRules(): array
    {
        return [
           //Hide action edit if user have not permission
            Rule::button('edit')
                ->when(fn() => auth()->user()->can('user-edit') === false)
                ->hide(),
 
           //Hide action delete if user have not permission
            Rule::button('destroy')
                ->when(fn() => auth()->user()->can('user-delete') === false)
                ->hide(),
        ];
    }
}
