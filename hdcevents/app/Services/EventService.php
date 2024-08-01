<?php 

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EventService
{
    protected $eventRepository;

    /**
     * EventService constructor.
     * 
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Retrieve events based on a search term or all events if no search term is provided.
     * 
     * @param string|null $search
     * @return Collection
     */
    public function getEvents($search): Collection
    {
        return $this->eventRepository->getEventsIfSearchOrNot($search);
    }

    /**
     * Create a new event.
     * 
     * @param array $request
     * @return Event
     */
    public function createNewEvent(array $request): Event
    {
        $data = $request;

        $data['user_id'] = auth()->user()->id;
        $data['image'] = $this->uploadImage($data['image']);

        return $this->eventRepository->createEvent($data);
    }

    /**
     * Update an existing event.
     * 
     * @param array $request
     * @param int $id
     * @return bool
     */
    public function updateEvent(array $request, int $id): bool
    {
        $data = $request;

        if (array_key_exists('image', $data) && $data['image']) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->eventRepository->updateEvent($data, $id);
    }

    /**
     * Delete an event and detach it from the user's events.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteEvent(int $id): bool
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        return $this->eventRepository->deleteEvent($id);
    }

    /**
     * Join an event.
     * 
     * @param int $id
     * @return Model
     */
    public function joinEvent(int $id): Model
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);

        return $this->eventRepository->getEventById($id);
    }

    /**
     * Leave an event.
     * 
     * @param int $id
     * @return Model
     */
    public function leaveEvent(int $id): Model
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        return $this->eventRepository->getEventById($id);
    }

    /**
     * Check if the user has joined an event.
     * 
     * @param Event $event
     * @return bool
     */
    public function hasUserJoined(Event $event): bool
    {
        $user = auth()->user();
        $userEvents = $user->eventsAsParticipant;

        foreach ($userEvents as $userEvent) {
            if ($userEvent->id === $event->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Upload an image and return its filename.
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @return string
     */
    private function uploadImage($image): string
    {
        $requestImage = $image;
        $extension = $requestImage->extension();
        $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $requestImage->move(public_path('img/events'), $imageName);

        return $imageName;
    }
}