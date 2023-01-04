<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCosmeticsEventRequest;
use App\Http\Requests\UpdateCosmeticsEventRequest;
use App\Http\Resources\CosmeticsEventResource;
use App\Http\Resources\CosmeticsEventResourceCollection;
use App\Models\CosmeticsEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CosmeticsEventController extends Controller
{
    public function getOne(int $id): CosmeticsEventResource|JsonResponse
    {
        $event = CosmeticsEvent::find($id);

        if (is_null($event)) {
            return response()->json(['Event not found'], 404);
        }

        return new CosmeticsEventResource($event);
    }

    public function list(): CosmeticsEventResourceCollection
    {
        return new CosmeticsEventResourceCollection(CosmeticsEvent::all());
    }

    public function create(StoreCosmeticsEventRequest $request): CosmeticsEventResource
    {
        $validated = $request->validated();
        $newEvent = CosmeticsEvent::create($validated);

        if (
            $request->hasFile('event_media')
            && $request->file('event_media')->isValid()
        ) {
            $newEvent->addMediaFromRequest('event_media')
                    ->toMediaCollection('event_media');
        }

        return new CosmeticsEventResource($newEvent);
    }

    public function update(UpdateCosmeticsEventRequest $request, int $id): CosmeticsEventResource|JsonResponse
    {
        $event = CosmeticsEvent::find($id);

        if (is_null($event)) {
            return response()->json(['Event not found'], 404);
        }

        $validated = $request->validated();

        foreach ($validated as $column => $value) {
            $event->$column = $value;
        }

        $event->save();

        return new CosmeticsEventResource($event);
    }

    public function delete(int $id): JsonResponse
    {
        $event = CosmeticsEvent::find($id);

        if (is_null($event)) {
            return response()->json(['Event not found'], 404);
        }

        $event->delete();

        return response()->json(['Successfully deleted']);
    }
}
