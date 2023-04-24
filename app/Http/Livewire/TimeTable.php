<?php

namespace App\Http\Livewire;

use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

final class TimeTable extends PowerGridComponent
{
    // Sort by default
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    
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
    * @return Builder<\App\Models\Ticket>
    */
    public function datasource(): Builder
    {
        return Ticket::query()
            ->leftJoin('companies', 'tickets.company_id', '=', 'companies.id')
            ->leftJoin('comments', 'tickets.id', '=', 'comments.ticket_id')
            ->select([
                'tickets.*',
                'companies.name as company_name',
                'comments.time_spent as comment_time_spent',
                'comments.content as comment_content',
                'comments.created_at as comment_created_at',
            ])
            ->whereNotNull('comments.time_spent')
            ->where('comments.time_spent', '!=', '');
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
            'comments' => [
                'content',
                'time_spent',
                'created_at',
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
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('ticket_number')
            ->addColumn('company_name')
            ->addColumn('comment_time_spent')
            // ->addColumn('comment_content')
            ->addColumn('comment_created_at', fn (Ticket $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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
            Column::make(trans('Ticket number'), 'ticket_number')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make(trans('Company'), 'company_name', 'companies.name')
                ->sortable()
                ->searchable()
                ->makeInputText(),
                
                Column::make(trans('Hour'), 'comment_time_spent', 'comments.time_spent')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            // Column::make(trans('Message'), 'comment_content', 'comments.content')
            //     ->sortable()
            //     ->searchable()
            //     ->makeInputText(),
                
            Column::make(trans('Created at'), 'comment_created_at', 'comments.created_at')
                ->searchable()
                ->sortable(),
                // ->makeInputDatePicker()
        ];
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
           Button::make('show', trans(''))
               ->class('btn-show')
               ->target('')
               ->route('tickets.show', ['ticket' => 'uuid']),
  
        ];
    }
}
