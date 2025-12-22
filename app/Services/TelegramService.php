<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token  = env('TELEGRAM_BOT_TOKEN');
        $this->chatId = env('TELEGRAM_CHAT_ID');
    }

    public function sendMessage($message)
    {
        return $this->sendMessageToChat($message, $this->chatId);
    }

    /**
     * Send message to a specific chat id (overrides env chat id when provided)
     * @param string $message
     * @param int|string|null $chatId
     * @return array|false
     */
    public function sendMessageToChat($message, $chatId = null)
    {
        $chat = $chatId ?? $this->chatId;

        if (empty($this->token) || empty($chat)) {
            Log::error('TelegramService missing token or chat id', ['token' => (bool)$this->token, 'chat' => $chat]);
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        try {
            $response = Http::post($url, [
                'chat_id' => $chat,
                'text'    => $message,
                'parse_mode' => 'HTML'
            ]);

            $json = $response->json();
            Log::info('TelegramService sent message', ['chat_id' => $chat, 'response' => $json]);
            return $json;
        } catch (Exception $e) {
            Log::error('TelegramService error', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
