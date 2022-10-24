<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        $this->beginDatabaseTransaction();

        $this->assertTrue(true);
        $this->visit('/')
            ->see('Login')
            ->see('Register');

        $this->visit('/auth/login')
            ->type('tiaant@saiba.org.za', 'email')
            ->type('P@ssw0rd', 'password')
            ->press('Sign in');

        $this->see('Administration')
            ->visit(route('admin.event.create'))
            ->type('My Test Event', 'name')
            ->type('2017-02-21', 'start_date')
            ->type('2017-02-21', 'end_date')
            ->type('2017-02-21', 'next_date')
            ->type('14:00', 'start_time')
            ->type('14:00', 'end_time')

            ->select('http://imageshack.com/a/img922/8194/PvOjE0.jpg','featured_image')
            ->press('Create Event')

            ->seeIsSelected('featured_image', 'http://imageshack.com/a/img922/8194/PvOjE0.jpg')
            ->type('Testing Short Description', 'short_description')
            ->type('Testing Description', 'description')
            ->press('Create Event')

            ->seePageIs('admin/event/show/my-test-event');


    }
}
