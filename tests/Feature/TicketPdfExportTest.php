<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketPdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_export_ticket_history_to_pdf()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $category = Category::create(['name' => 'General', 'is_active' => true]);
        Ticket::create([
            'user_id' => $staff->id,
            'category_id' => $category->id,
            'title' => 'Test Ticket',
            'description' => 'Test Description',
            'priority' => 'low',
            'status' => 'open'
        ]);

        $response = $this->actingAs($staff)->get(route('tickets.history.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_client_cannot_export_ticket_history_to_pdf()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('tickets.history.pdf'));

        $response->assertStatus(403);
    }
}
