<?php

namespace Tests\Feature;

use App\Http\Controllers\Ticket\TicketController;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TicketEditableTest extends TestCase
{
    use RefreshDatabase;

    public function testTicketCanNotBeEditableAfterSuperAdminSeen(): void
    {
        // Ticket Author
        // create user with permission "ticket-list"
        $ticketListPermission = Permission::create(['name' => 'ticket-list']);
        $user = User::factory()->create();
        $user->givePermissionTo($ticketListPermission);
        
        // Super Admin
        // create user with permission "all-access" and "ticket-list"
        $allAccessPermission = Permission::create(['name' => 'all-access']);
        $ticketPrivatePermission = Permission::create(['name' => 'ticket-private']);
        $admin = User::factory()->create();
        $admin->givePermissionTo($ticketListPermission);
        $admin->givePermissionTo($ticketPrivatePermission);
        $admin->givePermissionTo($allAccessPermission);
        
        // create ticket
        $ticket = Ticket::factory()->create([
            'user_id' => $user->id,
            'company_id' => 1,
            'ticket_number' => 'Test ticket_number',
            'subject' => 'Test subject',
            'uuid' => 'test-uuid',
            'state' => 'Non lu',
            'service' => 'Test service',
            'visibility' => 1,
            'hourly_rate' => 20,
            'notification_sent' => 0,
            'editable' => 1,
        ]);

        // create comment
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'content' => 'Test content',
            'time_spent' => null,
            'editable' => 1,
        ]);
        $comments = collect([$comment]);
        
        // authenticated admin and verify if he can shows ticket
        $response = $this->actingAs($admin)->get('/tickets/test-uuid');
        $response->assertOk();
        
        // verify ticket is editable before admin access ticket 
        $this->assertSame(1, $ticket->editable);
        
        // check if ticket editable from a controller method
        $controller = new TicketController();
        $controller->verifyTicketCanEditable($ticket, $comments);
        
        // verify ticket is not editable and state seen
        $this->assertSame(0, $ticket->editable, 'la valeur doit être 0, car non éditable');
        $this->assertSame('Lu', $ticket->state, "la valeur doit être 'Lu'");

        // verify comment is not editable
        $this->assertSame(0, $comment->editable, 'la valeur doit être 0, car non éditable');

    }

}
