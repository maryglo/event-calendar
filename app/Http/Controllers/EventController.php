<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Event;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    protected $events;

    public function __construct(Event $events)
    {
        $this->events = $events;
    }

    public function index(Request $request)
    {
        $data = $request->only(['month', 'year']);
        $date = Carbon::parse($data['year']."-".$data['month']."-01");
        $start = $date->startOfMonth()->format('Y-m-d H:i:s');

        return $this->events
            ->where('start_date', '<=', $start)
            ->where('end_date', '>=', $start)
            ->with('days')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $data = $request->only([
           'event_name', 'start_date', 'end_date'
        ]);

        $selectedDays = $request->input('selected_days');

        DB::beginTransaction();

        try {

            $eventNameExists = $this->events->whereEventName($data['event_name'])->first();
            $eventByDates = $this->events
                ->where('start_date', '>=', Carbon::parse($data['start_date'])->format('Y-m-d'))
                ->where('end_date', '<=', Carbon::parse($data['end_date'])->format('Y-m-d'))
                ->first();

            if ($eventNameExists) {
                $event = $this->deleteAndCreateEvent($eventNameExists, $data, $selectedDays);
            } else if ($eventByDates){
                $event = $this->deleteAndCreateEvent($eventByDates, $data, $selectedDays);
            } else {
                $event = $this->events->create($data);
                $event->days()->create($this->mapDays($selectedDays));
            }

            DB::commit();

        } catch (Throwable $err) {
            info($err->getMessage());
            return response()->json(
                [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'status' => 'success',
                    'message' => __('response.failed.action', ['item' => 'the event', 'action' => 'save'])
                ],
                Response::HTTP_OK
            );
        }

        return response()->json(
            [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => __('response.success.action', ['item' => 'Event', 'action' => 'saved']),
                'data' =>  $event->with('days')->get()
            ],
            Response::HTTP_OK
        );
    }

    private function deleteAndCreateEvent($model, $data, $days = [])
    {
        $model->days()->delete();
        $model->delete();
        $event  = $this->events->create($data);
        $event->days()->create($this->mapDays($days));

        return $event;
    }

    private function mapDays($selectedDays)
    {
        return collect($selectedDays)->mapWithKeys(function($day) {
            $dayIndex = strtolower($day);
            return [
                $dayIndex => 1
            ];
        })->toArray();
    }
}
