<?php

namespace App\Commands;

use App\Models\TelegramUser;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\User;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Запуск / Перезапуск бота';
    protected TelegramUser $telegramUser;

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }

    public function handle()
    {
        //Получаем всю информацию о пользователе
        $userData = $this->getUpdate()->message->from;
        //Получаем его уникальный ID
        $userId = $userData->id;
        //Пробуем найти юзера в БД
        $telegramUser = $this->telegramUser->where('user_id', '=', $userId)->first();
        //Проверяем, если нашли пользователя отправляем сообщение как старому
        //Иначе добавляем его в бд и отправялем сообщение как новому
        if ($telegramUser) {
            $this->sendAnswerForOldUsers();
        } else {
            $this->addNewTelegramUser($userData);
            $this->sendAnswerForNewUsers();
        }
    }

    /**
     * Добавление пользователя в базу данных.
     * @param User $userData
     * @return void
     */
    public function addNewTelegramUser(User $userData)
    {
        $this->telegramUser->insert([
            'user_id' => $userData->id,
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'language_code' => $userData->language_code,
            'is_premium' => $userData->is_premium,
            'is_bot' => $userData->is_bot,
        ]);
    }

    /**
     * Ответ старому пользователю.
     * @return void
     */
    public function sendAnswerForOldUsers(): void
    {
        $this->replyWithMessage([
            'text' => 'Рады видеть вас снова!🥳'
        ]);
    }

    /**
     * Ответ новому пользователю.
     * @return void
     */
    public function sendAnswerForNewUsers(): void
    {
        $this->replyWithMessage([
            'text' => 'Добро пожаловать в наш телеграм бот!'
        ]);
    }
}
