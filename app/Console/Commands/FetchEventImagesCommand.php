<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\UnreachableUrl;

class FetchEventImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:images:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Event::chunk(200, function ($events) {
            $events->filter(function ($event) {
                return !$event->hasMedia();
            })->filter(function ($event) {
                return !is_null($event->extra_info) && (array_key_exists('cover', $event->extra_info)
                        && array_key_exists('source', $event->extra_info['cover']));
            })->each(function(Event $event) {
                try {
                    $event->addMediaFromUrl($event->extra_info['cover']['source'])->toMediaCollection();
                } catch (UnreachableUrl $e) {
                    $this->output->error($event->id . " unreachable url " . $event->extra_info['cover']['source']);
                }
            });
        });
        
        return true;
    }
}
