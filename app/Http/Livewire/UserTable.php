<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UserTable extends PowerGridComponent
{
    use ActionButton;

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
        return User::query();
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
            ->addColumn('id')
            // ->addColumn('company_id')
            ->addColumn('job')

           /** Example of custom column using a closure **/
            ->addColumn('job_lower', function (User $model) {
                return strtolower(e($model->job));
            })

            // custom column company of user
            ->addColumn('user_company', function (User $user) {
                return strtolower(e(optional($user->company)->name));
            })
            ->addColumn('firstname')
            ->addColumn('lastname')
            ->addColumn('email')
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
            Column::make('ID', 'id')
                ->makeInputRange(),

            // Column::make('COMPANY ID', 'company_id')
            //     ->makeInputRange(),
           
            Column::make(trans('Company'), 'user_company')
                ->makeInputText(),

            Column::make(trans('Job'), 'job')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Firstname'), 'firstname')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Lastname'), 'lastname')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Email'), 'email')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Created at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

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
            Button::make('show', trans('Show'))
                 ->class('inline-block ml-4 py-1 align-middle text-center font-medium hover:underline transition duration-150 ease-in-out')
                 ->target('')
                 ->route('users.show', ['user' => 'id']),
                 
            Button::make('edit', trans('Edit'))
                 ->class('inline-block ml-4 py-1 align-middle text-center font-medium hover:underline transition duration-150 ease-in-out')
                 ->target('')
                 ->route('users.edit', ['user' => 'id']),
                 
             Button::make('destroy', trans('Delete'))
                 ->class('inline-block ml-4 py-1 align-middle text-center font-medium text-red-600 hover:underline transition duration-150 ease-in-out')
                 ->target('')
                 ->route('users.destroy', ['user' => 'id'])
                 ->method('delete')
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
