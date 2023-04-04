<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class TicketTable extends PowerGridComponent
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
    * @return Builder<\App\Models\Ticket>
    */
    public function datasource(): Builder
    {
        $query = Ticket::query()
            ->leftJoin('companies', 'tickets.company_id', '=', 'companies.id')
            ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
            ->select([
                'tickets.*',
                DB::raw("CONCAT(users.firstname, ' ', users.lastname) as user_fullname"),
                'companies.name as company_name',
            ]);
        
        // if user authenticate have all-access, can see every tickets of DB
        if (auth()->user()->can('all-access')) {
            return $query;

        } else { // else only users of his company
            
            // if user have permission ticket-private (admin-company)
            if (auth()->user()->can('ticket-private')) {
                $query->where('tickets.company_id', '=', auth()->user()->company_id);
                return $query;
                
            } else {// else simple user of company
                $query->where('tickets.company_id', '=', auth()->user()->company_id)
                // if auth->user is not author of tickets, he looks only with visibility = 1 (public)
                ->when(auth()->user()->id !== auth()->user()->tickets()->pluck('id'), function ($query) {
                    $query->where('visibility', '=', '1');
                })
                // if user is author of tickets, he looks visibility 0 and 1 (private and public)
                ->orWhere(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                    ->whereIn('visibility', [0, 1]);
                });
                return $query;
            }
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
            'user' => [
                'firstname',
                'lastname',
            ],
            'company' => [
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
            ->addColumn('user_fullname')
            ->addColumn('company_name')
            ->addColumn('ticket_number')
            // ->addColumn('subject')
            // ->addColumn('uuid')
            ->addColumn('state')
            // ->addColumn('service')
            ->addColumn('visibility', fn (Ticket $ticket) => $ticket->visibility ? 'Publique' : 'Privée')
            ->addColumn('created_at_formatted', fn (Ticket $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('updated_at_formatted', fn (Ticket $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'))
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

            Column::make(trans('Author'), 'user_fullname', 'users.firstname')
                ->sortable()
                ->searchable()
                ->makeInputText(),
         
            Column::make(trans('Company'), 'company_name', 'companies.name')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Ticket number'), 'ticket_number')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            // Column::make(trans('Subject'), 'subject')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            // Column::make('UUID', 'uuid')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            Column::make(trans('State'), 'state')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            // Column::make(trans('Service'), 'service')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),

            Column::make(trans('Visibility'), 'visibility')
                // ->searchable()
                ->sortable(),

            Column::make(trans('Created at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),
                // ->makeInputDatePicker()

            Column::make(trans('Updated at'), 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable()
                //  ->makeInputDatePicker(),

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
     * PowerGrid Ticket Action Buttons.
     *
     * @return array<int, Button>
     */

     public function actions(): array
     {
        return [
            Button::make('show', trans('Show'))
                ->class('inline-block ml-4 py-1 align-middle text-center font-medium hover:underline transition duration-150 ease-in-out')
                ->target('')
                ->route('tickets.show', ['ticket' => 'uuid']),

            Button::make('edit', trans('Edit'))
                ->class('inline-block ml-4 py-1 align-middle text-center font-medium hover:underline transition duration-150 ease-in-out')
                ->target('')
                ->route('tickets.edit', ['ticket' => 'uuid']),
                
            Button::make('destroy', trans('Delete'))
                ->class('inline-block ml-4 py-1 align-middle text-center font-medium text-red-600 hover:underline transition duration-150 ease-in-out')
                ->target('')
                ->route('tickets.destroy', ['ticket' => 'uuid'])
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
     * PowerGrid Ticket Action Rules.
     *
     * @return array<int, RuleActions>
     */

     public function actionRules(): array
     {
        return [
 
            //Hide action edit if user have not permission
             Rule::button('edit')
                 ->when(fn() => auth()->user()->can('ticket-edit') === false)
                 ->when(fn(Ticket $ticket) => $ticket->user_id !== auth()->user()->id)
                 ->hide(),
 
            //Hide action delete if user have not permission
             Rule::button('destroy')
                 ->when(fn() => auth()->user()->can('ticket-delete') === false)
                 ->hide(),
         ];
     }
}
