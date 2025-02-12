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
            ->subject('Заказ №' . $this->order->id . ' выполнен')
            ->greeting('Здравствуйте, ' . $this->order->name . '!')
            ->line('Ваш заказ успешно выполнен и доставлен.')
            ->line('Детали заказа:')
            ->line('Номер заказа: №' . $this->order->id)
            ->line('Сумма заказа: ' . number_format($this->order->total_amount, 2) . ' MDL')
            ->line('Способ доставки: ' . ($deliveryMethod ? $deliveryMethod->name : 'Не указан'))
            ->line('Адрес доставки: ' . $this->order->address)
            ->action('Посмотреть заказ', $url)
            ->line('Спасибо, что выбрали наш магазин!')
            ->line('Пожалуйста, не забудьте оставить отзыв о приобретенных товарах - это поможет другим покупателям сделать правильный выбор.')
            ->line('Будем рады видеть вас снова!')
            ->salutation('С уважением, Команда MoldavianHandmade');
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
