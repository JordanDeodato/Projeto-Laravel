<?php 

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventRepository
{
    /**
     * Get events based on search query or all events if no search query.
     *
     * @param string|null $search
     * @return Collection
     */
    public function getEventsIfSearchOrNot(?string $search): Collection
    {
        return $search ? Event::where('title', 'like', "%{$search}%")->get() : Event::all();
    }

    /**
     * Get an event by its ID.
     *
     * @param int $id
     * @return Event|null
     */
    public function getEventById(int $id): ?Event
    {
        return Event::find($id);
    }

    /**
     * Create a new event.
     *
     * @param array $data
     * @return Event
     */
    public function createEvent(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Update an existing event.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateEvent(array $data, int $id): bool
    {
        $event = Event::findOrFail($id);
        return $event->update($data);
    }

    /**
     * Delete an event by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEvent(int $id): bool
    {
        $event = Event::findOrFail($id);
        return $event->delete();
    }
}
