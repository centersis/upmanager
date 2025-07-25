<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ShowPasswordResetLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:show-reset-links {--latest=5 : Number of latest links to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show password reset links from the log (useful in development)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return 1;
        }

        $this->info('🔍 Searching for password reset links in log...');
        $this->newLine();

        // Read log file
        $logContent = file_get_contents($logPath);
        
        // Extract reset password links with better regex
        preg_match_all('/http:\/\/localhost:8000\/reset-password\/[a-f0-9]+\?email=[^"\s&<>]+/', $logContent, $matches);
        
        if (empty($matches[0])) {
            $this->warn('❌ No password reset links found in the log.');
            $this->info('💡 Make sure to request a password reset first!');
            return 0;
        }

        // Clean and get unique links (latest first)
        $cleanLinks = [];
        foreach ($matches[0] as $link) {
            // Clean any HTML artifacts
            $cleanLink = preg_replace('/[<>"].*$/', '', $link);
            $cleanLink = trim($cleanLink);
            if (!empty($cleanLink) && !in_array($cleanLink, $cleanLinks)) {
                $cleanLinks[] = $cleanLink;
            }
        }
        
        $links = array_reverse($cleanLinks);
        $limit = (int) $this->option('latest');
        
        if ($limit > 0) {
            $links = array_slice($links, 0, $limit);
        }

        $this->info("✅ Found " . count($cleanLinks) . " password reset link(s). Showing latest {$limit}:");
        $this->newLine();

        foreach ($links as $index => $link) {
            // Extract email from link
            preg_match('/email=([^&]+)/', $link, $emailMatch);
            $email = isset($emailMatch[1]) ? urldecode($emailMatch[1]) : 'Unknown';
            
            $this->line("📧 <fg=cyan>Reset Link #" . ($index + 1) . "</>");
            $this->line("   👤 Email: <fg=yellow>{$email}</>");
            $this->line("   🔗 Link:  <fg=green>{$link}</>");
            $this->newLine();
        }

        $this->info('💡 Copy and paste any link above in your browser to reset the password!');
        $this->info('⏰ Remember: Reset links expire in 60 minutes.');
        
        return 0;
    }
}
