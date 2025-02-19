<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompleted extends Notification
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
            ->subject(__('notifications.order_completed.subject', ['number' => $this->order->id]))
            ->greeting(__('notifications.order_completed.greeting', ['name' => $this->order->name]))
            ->line(__('notifications.order_completed.completed_message'))
            ->line(__('notifications.order_completed.order_details'))
            ->line(__('notifications.order_completed.order_number', ['number' => $this->order->id]))
            ->line(__('notifications.order_completed.order_amount', [
                'amount' => number_format($this->order->total_amount, 2),
                'currency' => 'MDL'
            ]))
            ->line(__('notifications.order_completed.delivery_method', [
                'method' => $deliveryMethod ? $deliveryMethod->name : __('delivery.not_specified.name')
            ]))
            ->line(__('notifications.order_completed.delivery_address', ['address' => $this->order->address]))
            ->action(__('notifications.order_completed.view_order'), $url)
            ->line(__('notifications.order_completed.thank_you'))
            ->line(__('notifications.order_completed.review_request'))
            ->line(__('notifications.order_completed.come_back'))
            ->salutation(__('notifications.order_completed.signature'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
