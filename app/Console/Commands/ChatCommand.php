<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AI\OpenAIChat;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//         $question = $this->ask('What is your  question for AI');
        $question = text('What is your  question for AI');

        $chat = new OpenAIChat();

        $response = $chat->send($question);

//        $this->info($response);
        info($response);

       while($this->ask('Do you want to respond?')){
        $question = text('What is your reply?');
        $response = $chat->send($question);

         info($response);
       }

        $this->info('Conversation over');

    }
}
