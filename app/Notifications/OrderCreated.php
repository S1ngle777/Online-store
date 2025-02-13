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
            ->subject('Заказ №' . $this->order->id . ' успешно оформлен')
            ->greeting('Здравствуйте, ' . $this->order->name . '!')
            ->line('Спасибо за ваш заказ в нашем магазине!')
            ->line('Детали заказа:')
            ->line('Номер заказа: №' . $this->order->id)
            ->line('Сумма заказа: ' . number_format($this->order->total_amount, 2) . ' MDL')
            ->line('Способ доставки: ' . ($deliveryMethod ? $deliveryMethod->name : 'Не указан'))
            ->line('Адрес доставки: ' . $this->order->address)
            ->action('Посмотреть заказ', $url)
            ->line('Мы начали обработку вашего заказа и свяжемся с вами в ближайшее время.')
            ->line('Если у вас возникли вопросы, пожалуйста, свяжитесь с нами.')
            ->salutation('С уважением, команда Handmade.md');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
