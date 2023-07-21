<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\Authorization as Organization ;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testOrganization = Organization::factory(1)->make()->first();
        $testOrganization->name = 'test-company';
        $testOrganization->secret = 'secret';
        $testOrganization->save();
        $testOrganization->events()->saveMany(Event::factory(100)->make());


        Organization::factory(10)->create()->each(function ($organization) {
            $organization->events()->saveMany(Event::factory(10)->make());
        });
    }
}
