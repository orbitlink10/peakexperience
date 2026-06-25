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
            'case-study' => 'Our Work',
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

    public function test_pages_group_stays_active_for_homepage_editor(): void
    {
        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->get(route('admin.homepage'));

        $response->assertOk();

        $content = $response->getContent();

        $this->assertIsString($content);
        $this->assertMatchesRegularExpression(
            '/href="' . preg_quote(route('admin.section', ['section' => 'pages']), '/') . '"[^>]*nav-link active[^>]*>.*?<p>Pages<\/p>/s',
            $content
        );
        $this->assertMatchesRegularExpression(
            '/href="' . preg_quote(route('admin.homepage'), '/') . '"[^>]*nav-link nav-child active[^>]*aria-current="page"[^>]*>.*?<p>Homepage<\/p>/s',
            $content
        );
    }
}
