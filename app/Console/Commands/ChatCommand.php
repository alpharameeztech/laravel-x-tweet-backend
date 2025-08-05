<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AI\OpenAIChat;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat {--system=}';

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
        $chat = new OpenAIChat();

        if($this->option('system')){
            $chat->systemMessage($this->option('system'));
        }
//         $question = $this->ask('What is your  question for AI');
        $question = text(
            label: 'What is your  question for AI',
            required: true
        );


//         $response = $chat->send($question);
            $response = spin(fn() => $chat->send($question), 'Sending request...');
//        $this->info($response);
        info($response);

       while($this->ask('Do you want to respond?')){
        $question = text('What is your reply?');
        $response = spin(fn() => $chat->send($question), 'Sending request...');

         info($response);
       }

        $this->info('Conversation over');

    }
}
