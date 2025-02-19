<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreated extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/orders/' . $this->order->id);
        $deliveryMethod = $this->order->deliveryMethod;

        return (new MailMessage)
            ->subject(__('notifications.order_created.subject', ['number' => $this->order->id]))
            ->greeting(__('notifications.order_created.greeting', ['name' => $this->order->name]))
            ->line(__('notifications.order_created.thank_you'))
            ->line(__('notifications.order_created.order_details'))
            ->line(__('notifications.order_created.order_number', ['number' => $this->order->id]))
            ->line(__('notifications.order_created.order_amount', [
                'amount' => number_format($this->order->total_amount, 2),
                'currency' => 'MDL'
            ]))
            ->line(__('notifications.order_created.delivery_method', [
                'method' => $deliveryMethod ? $deliveryMethod->name : __('delivery.not_specified.name')
            ]))
            ->line(__('notifications.order_created.delivery_address', ['address' => $this->order->address]))
            ->action(__('notifications.order_created.view_order'), $url)
            ->line(__('notifications.order_created.processing_info'))
            ->line(__('notifications.order_created.contact_us'))
            ->salutation(__('notifications.order_created.signature'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
