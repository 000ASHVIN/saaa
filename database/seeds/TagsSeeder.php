<?php

use App\Store\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create([
            'title' => 'DVD',
            'description' => 'To play this product you will need a computer or laptop with a DVD drive.',
            'icon_classes' => 'fa fa-play-circle'
        ]);
        Tag::create([
            'title' => 'Printed course notes',
            'description' => 'You will receive a printed book with all the course notes.',
            'icon_classes' => 'fa fa-book'
        ]);
        Tag::create([
            'title' => 'Extra hour CPD',
            'description' => 'Complete a short quiz and get an extra hour of CPD',
            'icon_classes' => 'fa fa-clock-o'
        ]);
    }
}
