<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Models\User;

class TokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate {id}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');

        $user = User::find($id);

        \Auth::setUser($user);

        $console = new ConsoleOutput();

        $console->writeln($user->createToken('admin')->accessToken);

    }
}
