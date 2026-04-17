<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminSidebarTest extends TestCase
{
    public function test_sidebar_sections_have_real_routes(): void
    {
        $session = ['admin_authenticated' => true, 'admin_username' => 'admin'];

        foreach ([
            'overview' => 'Overview',
            'services' => 'Services',
            'team' => 'Team',
            'sliders' => 'Sliders',
            'clients' => 'Clients',
            'invoices' => 'Invoices',
            'videos' => 'Videos',
            'pages' => 'Pages',
            'contact-page' => 'Contact Page',
        ] as $slug => $heading) {
            $this
                ->withSession($session)
                ->get(route('admin.section', ['section' => $slug]))
                ->assertOk()
                ->assertSee($heading);
        }
    }
}
