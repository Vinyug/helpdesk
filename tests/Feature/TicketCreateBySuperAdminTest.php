<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Company;
use App\Models\Listing;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;


class TicketCreateTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTicketCanBeRendered(): void
    {
        // create permission ticket-create
        $permission = Permission::create(['name' => 'ticket-create']);

        // create user with permission ticket-create
        $user = User::factory()->create();
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)->get('/tickets/create');

        $response->assertOk();
    }

    public function testStoreTicketWithRequiredFields(): void
    {
        $ticketCreatePermission = Permission::create(['name' => 'ticket-create']);
        $ticketPrivatePermission = Permission::create(['name' => 'ticket-private']);
        $allAccessPermission = Permission::create(['name' => 'all-access']);
        $company = Company::factory()->create();
        $listingHourlyRate = Listing::factory()->create(['hourly_rate' => '20']);

        $user = User::factory()->create();
        $user->givePermissionTo($ticketCreatePermission);
        $user->givePermissionTo($ticketPrivatePermission);
        $user->givePermissionTo($allAccessPermission);
        $user->company_id = $company->id;
        $user->save();

        $data = [
            'company_id' => $user->company_id,
            'subject' => 'Test Subject',
            'service' => Listing::factory()->create()->service,
            'content' => 'Test content',
            'visibility' => 0,
            'hourly_rate' => $listingHourlyRate->hourly_rate,
        ];

        $response = $this->actingAs($user)->post('/tickets', $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $ticket = Ticket::latest()->first();
        $comment = new Comment([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'content' => 'Test comment',
        ]);
        $comment->save();

        $this->assertDatabaseHas('tickets', [
            'company_id' => $data['company_id'],
            'subject' => $data['subject'],
            'service' => $data['service'],
            'visibility' => !$data['visibility'],
            'hourly_rate' => $data['hourly_rate'],
        ]);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'content' => 'Test comment',
        ]);

    }

}
