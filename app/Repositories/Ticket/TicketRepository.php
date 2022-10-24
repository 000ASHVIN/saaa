<?php
namespace App\Repositories\Ticket;


use App\AppEvents\DietaryRequirement;
use App\AppEvents\Extra;
use App\AppEvents\Ticket;
use App\Billing\Invoice;
use App\Billing\Item;

class TicketRepository
{
    protected $products;

    public function createTicket($user, $pricing, $venue, $event)
    {
        $ticket = new Ticket;
        $code = str_random(20);
        $ticket->code = $code;
        $ticket->name = $pricing->name;
        $ticket->description = $pricing->description;
        $ticket->first_name = $user->first_name;
        $ticket->last_name = $user->last_name;
        $ticket->user_id = $user->id;
        $ticket->event_id = $event->id;
        $ticket->venue_id = $venue->id;
        $ticket->pricing_id = $pricing->id;
        $ticket->invoice_id = 0;
        $ticket->dietary_requirement_id = 0;
        $ticket->email = $user->email;
        return $ticket;
    }

    public function generateTicketInvoice($user, $pricing)
    {
        $invoice = new Invoice;
        $invoice->type = 'event';
        $invoice->setUser($user);
        $invoice->save();

        $item = new Item;
        $item->type = 'ticket';
        $item->name = $pricing->event->name;
        $item->description = $pricing->description . ' - ' . $pricing->venue->name;
        $item->price = $pricing->price;
        $item->discount = ($pricing->getDiscountForUser($user) + $pricing->getPromoCodesDiscount());
        $item->item_id = $pricing->event->id;
        $item->item_type = get_class($pricing->event);
        $item->save();

        $this->products[] = $item;
        return $invoice;
    }

    public function generateTicketItem($user, $pricing)
    {
        $item = new Item;
        $item->type = 'ticket';
        $item->name = $pricing->event->name;
        $item->description = $pricing->description . ' - ' . $pricing->venue->name;
        $item->price = $pricing->price;
        $item->discount = ($pricing->getDiscountForUser($user) + $pricing->getPromoCodesDiscount() + $pricing->getCPDSubscriberDiscount($user));
        $item->item_id = $pricing->event->id;
        $item->item_type = get_class($pricing->event);
        $item->save();

        $this->products[] = $item;
    }

    public function setExtras($ticket, $invoice, $extraId)
    {
        $extra = Extra::find($extraId);
        if ($extra->price > 0)
            $this->addExtraToInvoice($extra, $invoice, $ticket->event->name);
        $ticket->extras()->attach($extra);
    }

    public function addExtraToInvoice($extra, $invoice, $event_name)
    {
        $item = new Item;
        $item->type = 'product';
        $item->name = $extra->name;
        $item->description = $event_name;
        $item->price = $extra->price;
        $item->item_id = $extra->id;
        $item->item_type = get_class($extra);
        $item->save();

        $this->products[] = $item;
    }

    public function setDietary($invoice, $dietary, $dates)
    {
        $multiplier = count($dates);
        $toAdd = DietaryRequirement::find($dietary);
        if ($toAdd->price > 0) {
            for ($i=1; $i <= $multiplier; $i++) {
                $this->addDietaryToInvoice($toAdd, $invoice);
            }
        }
    }

    public function addDietaryToInvoice($dietary, $invoice)
    {
        $item = new Item;
        $item->type = 'product';
        $item->name = $dietary->name;
        $item->description = 'Dietary Requirement';
        $item->price = $dietary->price;
        $item->item_id = $dietary->id;
        $item->item_type = get_class($dietary);
        $item->save();

        $this->products[] = $item;
    }

    public function finaltouches($invoice)
    {
        $invoice->addItems($this->products);
        $invoice->autoUpdateAndSave();

        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'display_type' => 'Invoice',
            'status' => ($invoice->paid) ? 'Closed' : 'Open',
            'category' => $invoice->type,
            'amount' => $invoice->total,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => "Invoice #{$invoice->reference}",
            'tags' => "Invoice",
            'date' => $invoice->created_at
        ]);

        $invoice->autoUpdateDiscount();
        $discount_transaction = $invoice->fresh()->transactions()->where('tags', 'Discount')->first();
        if($discount_transaction) {
            $invoice->discount = $discount_transaction->amount;
            $invoice->save();
            $invoice->fresh()->autoUpdateAndSave();
        }
    }
}