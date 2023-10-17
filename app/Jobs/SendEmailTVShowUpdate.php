<?php

namespace App\Jobs;

use App\Mail\TVShowsUpdatesNotif;
use App\Models\EmailSubscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailTVShowUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected EmailSubscription $subscription)
    {

    }

    /**
     * send email to user
     */
    public function handle(): void
    {
        $message = (new TVShowsUpdatesNotif($this->subscription->user))->onQueue('emails');

        $isSent = false;
        $exception = null;

        // send
        try {
            Mail::to($this->subscription->user->email)->send($message);
            $isSent = true;
        }
        catch (\Exception $exception) {
            logException($exception, "Send Email Subscription JOB");
        }
        finally {
            $this->subscription->addNewTry($isSent);

            // if is not sent then throw exception to fail the job
            // so it can be retried
//            if(! $isSent) {
//                throw $exception;
//            }
        }


    }
}
