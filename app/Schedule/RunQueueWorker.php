<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Throwable;

class RunQueueWorker
{
    public function __invoke(): void
    {
        Log::info('⏰ Running shared-hosting safe queue processor...');

        try {
            // Fetch one pending job from the database queue
            $jobRecord = DB::table('jobs')->orderBy('id')->first();

            if (!$jobRecord) {
                Log::info('✅ No jobs in queue to process.');
                return;
            }

            Log::info('⚙️ Processing job ID: ' . $jobRecord->id);

            // Decode payload
            $payload = json_decode($jobRecord->payload, true);

            if (!isset($payload['data']['command'])) {
                Log::warning('Job payload malformed', ['job' => $jobRecord]);
                return;
            }

            // Unserialize job command and handle it
            $job = unserialize($payload['data']['command']);

            if (method_exists($job, 'handle')) {
                $job->handle(); // run the job logic directly
                Log::info('✅ Job ID ' . $jobRecord->id . ' executed successfully.');

                // Remove from queue
                DB::table('jobs')->where('id', $jobRecord->id)->delete();
            } else {
                Log::warning('Job has no handle() method', ['job' => $jobRecord]);
            }

        } catch (Throwable $e) {
            Log::error('❌ Queue runner failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
