<?php

namespace App\Livewire;

// use Filament\Widgets\Widget;
use App\Filament\Resources\AppointmentResource;
use App\Filament\Resources\TaskResource;
use App\Models\Appointment;
use App\Models\Task;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class TaskCalendar extends FullCalendarWidget
{
    // protected static string $view = 'livewire.task-calendar';

    public function fetchEvents(array $fetchInfo): array
    {
        return Appointment::query()
            ->where('scheduled_at', '>=', $fetchInfo['start'])
            ->where('scheduled_at', '<=', $fetchInfo['end'])
            ->when(!auth()->user()->isAdmin(), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->get()
            ->map(
                fn(Appointment $task) => EventData::make()
                    ->id($task->id)
                    ->title(strip_tags($task->description))
                    ->start($task->scheduled_at)
                    ->end($task->scheduled_at)
                    ->url(AppointmentResource::getUrl('edit', [$task->id]))
                    ->toArray()
            )
            ->toArray();
    }

}
