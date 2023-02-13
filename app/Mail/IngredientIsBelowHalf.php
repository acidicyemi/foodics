<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class IngredientIsBelowHalf extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ingredients;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.ingredient.below_half', ["ingredients" => $this->ingredients]);
    }
}
