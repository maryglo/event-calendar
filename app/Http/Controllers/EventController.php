<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
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
        $date = \Carbon\Carbon::parse($data['year']."-".$data['month']."-01");
        $start = $date->startOfMonth()->format('Y-m-d H:i:s');
        $end = $date->endOfMonth()->format('Y-m-d H:i:s');

        return $this->events
            ->where('start_date', '>=', $start)
            ->where('end_date', '<=', $end)
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

        DB::beginTransaction();

        try {

            $eventNameExists = $this->events->whereEventName($data['event_name'])->first();
            $eventByDates = $this->events
                ->where('start_date', '>=', $data['start_date'])
                ->where('end_date', '<=', $data['end_date'])
                ->first();

            if ($eventNameExists) {
                $this->deleteAndCreateEvent($eventNameExists, $data);
            } else if ($eventByDates){
                $this->deleteAndCreateEvent($eventByDates, $data);
            } else {
                $this->events->create($data);
            }

            DB::commit();

        } catch (Throwable $err) {
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
                'message' => __('response.success.action', ['item' => 'Event', 'action' => 'saved'])
            ],
            Response::HTTP_OK
        );
    }

    private function deleteAndCreateEvent($model, $data)
    {
        $model->delete();
        $this->events->create($data);
    }
}
