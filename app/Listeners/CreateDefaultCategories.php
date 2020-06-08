<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use IlluminateAuthEventsRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDefaultCategories
{
    public $categories = ['Salario', 'Arriendo', 'Alimentacion', 'Transporte', 'Ocio', 'Otros'];
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  IlluminateAuthEventsRegistered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        collect($this->categories)->each(function ($category) use($user){
            $user->categories()->create([
                'name'  => $category
            ]);
        });
    }
}
