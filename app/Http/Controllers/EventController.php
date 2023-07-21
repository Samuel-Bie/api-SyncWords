<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollectionResource;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = $request->user()->events()->get();

        return response()->json([
            'data' => new EventCollectionResource($events)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $event = new Event($request->validated());
        $event->organization_id = $request->user()->id;
        $event->save();

        return response()->json(
            new EventResource($event),
            HttpStatusCode::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event)
    {
        $response = Gate::inspect('view', $event);

        if (!$response->allowed()) {
            return response()->json([
                'message' => $response->message()
            ], HttpStatusCode::HTTP_FORBIDDEN);
        }
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $response = Gate::inspect('update', $event);

        if (!$response->allowed()) {
            return response()->json([
                'message' => $response->message()
            ], HttpStatusCode::HTTP_FORBIDDEN);
        }

        $event->update($request->validated());

        return response()->json(
            new EventResource($event),
            HttpStatusCode::HTTP_ACCEPTED,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $response = Gate::inspect('delete', $event);

        if (!$response->allowed()) {
            return response()->json([
                'message' => $response->message()
            ], HttpStatusCode::HTTP_FORBIDDEN);
        }
        $event->delete();

        return response()->json(
            null,
            HttpStatusCode::HTTP_NO_CONTENT,
        );
    }
}
