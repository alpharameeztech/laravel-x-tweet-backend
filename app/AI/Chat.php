<?php

namespace App\AI;

use Illuminate\Support\Facades\Http;

class Chat
{
    protected array $messages = [];

    public function messages()
    {
        return $this->messages;
    }

    public function systemMessage(string $message): static
    {
       $this->messages[] = [
                'role' => 'system',
                'content' => $message,
            ];
       return $this;
    }

    public function send(string $message): ?string
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4.1-nano',
            'messages' => $this->messages,
        ]);

        if($response){
            $assistantReply = $response->json('choices.0.message.content') ?? 'No response';

                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $assistantReply,
                ];
        }

        return $assistantReply;
    }

    public function reply(string $message): ?string
    {
        return $this->send($message);
    }
}
