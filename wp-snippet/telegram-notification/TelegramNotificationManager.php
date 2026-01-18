<?php

class TelegramNotificationManager
{
    private string $botToken;
    private string $chatId;
    private string $apiUrl;

    public function __construct(string $botToken, string $chatId)
    {
        $this->botToken = $botToken;
        $this->chatId   = $chatId;
        $this->apiUrl   = "https://api.telegram.org/bot{$this->botToken}/";
    }

    /**
     * SATU METHOD SAJA
     * Kirim pesan Telegram
     */
    public function send(string $message, array $options = []): bool
    {
        $payload = array_merge([
            'chat_id'    => $this->chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ], $options);

        return $this->request('sendMessage', $payload);
    }

    /**
     * INTERNAL REQUEST
     */
    private function request(string $endpoint, array $data): bool
    {
        $response = wp_remote_post($this->apiUrl . $endpoint, [
            'timeout' => 10,
            'body'    => $data,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        return isset($body['ok']) && $body['ok'] === true;
    }
}
