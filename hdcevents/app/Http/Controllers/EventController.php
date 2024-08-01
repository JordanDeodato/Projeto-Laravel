<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Event;
use App\Services\EventService;
use Exception;

class EventController extends Controller
{
    protected $eventService;

    /**
     * EventController constructor.
     *
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Display a listing of events.
     *
     * @return View|RedirectResponse
     */
    public function getEvents(): View|RedirectResponse
    {
        try {
            $search = request('search');
            $events = $this->eventService->getEvents($search);

            return view('welcome', [
                'events' => $events,
                'search' => $search
            ]);
        } catch (Exception $e) {
            logger()->error('Error fetching events', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao buscar os eventos.');
        }
    }

    /**
     * Show the form to create a new event.
     *
     * @return View
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createNewEvent(EventRequest $request): RedirectResponse
    {
        try {
            $this->eventService->createNewEvent($request->validated());

            return redirect('/')
                ->with('msg', 'Evento criado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            logger()->error('Error creating event', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao criar o evento.')
                ->withInput();
        }
    }

    /**
     * Display the specified event.
     *
     * @param Event $event
     * @return View
     */
    public function show(Event $event): View
    {
        $hasUserJoined = $this->eventService->hasUserJoined($event);

        return view('events.show', [
            'event' => $event,
            'eventOwner' => $event->user,
            'hasUserJoined' => $hasUserJoined
        ]);
    }

    /**
     * Display the dashboard with the user's events.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        return view('events.dashboard', [
            'events' => $user->events,
            'eventsasparticipant' => $user->eventsAsParticipant
        ]);
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param Event $event
     * @return RedirectResponse|View
     */
    public function edit(Event $event): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->id !== $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified event in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function updateEvent(UpdateEventRequest $request, int $id): RedirectResponse
    {
        try {
            $this->eventService->updateEvent($request->validated(), $id);

            return redirect('/dashboard')
                ->with('msg', 'Evento editado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            logger()->error('Error updating event', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao editar o evento.')
                ->withInput();
        }
    }

    /**
     * Remove the specified event from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteEvent(int $id): RedirectResponse
    {
        try {
            $this->eventService->deleteEvent($id);

            return redirect('/dashboard')
                ->with('msg', 'Evento excluído com sucesso!');
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            logger()->error('Error deleting event', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao excluir o evento.');
        }
    }

    /**
     * Handle a user joining an event.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function joinEvent(int $id): RedirectResponse
    {
        try {
            $event = $this->eventService->joinEvent($id);

            return redirect('/dashboard')
                ->with('msg', "Sua presença foi confirmada no evento {$event->title}");
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            logger()->error('Error joining event', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao confirmar a presença no evento.');
        }
    }

    /**
     * Handle a user leaving an event.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function leaveEvent(int $id): RedirectResponse
    {
        try {
            $event = $this->eventService->leaveEvent($id);

            return redirect('/dashboard')
                ->with('msg', "Você saiu com sucesso do evento: {$event->title}");
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            logger()->error('Error leaving event', ['exception' => $e]);

            return redirect()
                ->back()
                ->with('error', 'Ocorreu um erro ao sair do evento.');
        }
    }
}
