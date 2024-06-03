<?php

namespace App\Console\Commands;

use App\Mail\DailySummaryMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySummary extends Command
{
        /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily summary of unread articles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //ForEach user
        foreach (User::all() as $user )
        {
            $articles = Article::forUser($user)
            ->where('published_at', '>=', now()->startOfDay()->subDay())
            ->get();

            if (!$articles->isEmpty()) {
                 // Send email
                $this->info('Sending emails');
                Mail::to($user->email)->send(new DailySummaryMail($articles));
            }
        }

        $this->info('Daily summary email sent successfully.');
        return 0;
    }
}
