<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\Facebook;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
    protected $description = 'Fetches images for the events that does not have an image.';
    
    /**
     * @var Facebook
     */
    private $fb;
    
    /**
     * Create a new command instance.
     *
     * @param Facebook $fb
     */
    public function __construct(Facebook $fb)
    {
        parent::__construct();
        $this->fb = $fb;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Event::whereBetween('created_at', [
            Carbon::now()->subDay(),
            Carbon::now()
        ])->chunk(200, function (Collection $events) {
            $events = $events->reject(function (Event $event) {
                return $event->hasMedia('cover');
            });
    
            /** @var Event $event */
            foreach ($events as $event) {
                try {
                    $response = $this->fb->sendRequest('GET', $event->facebook_id, ['fields' => 'cover'])->getDecodedBody();
                    $url = data_get($response, 'cover.source') ?? '';
                    
                    $event->addMediaFromUrl($url)->toMediaCollection('cover');
                } catch (UnreachableUrl $e) {
                    $this->output->error($event->id . " returned " . print_r($response));
                } catch (FacebookResponseException $exception) {
                    $this->output->error("Event with facebook id {$event->facebook_id}, does not exists");
                } catch (\Exception $e) {
                    $this->output->error("Huge error!");
                }
            }
        });
        
        return true;
    }
}
