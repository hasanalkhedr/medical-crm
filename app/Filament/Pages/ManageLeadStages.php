<?php

namespace App\Filament\Pages;

use App\Models\Lead;
use Filament\Pages\Page;
use Livewire\Attributes\On;
use App\Models\PipelineStage;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class ManageLeadStages extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-s-view-columns';
    protected static string $view = 'filament.pages.manage-lead-stages';
    protected ?string $heading = 'Lead Pipeline Board';
    protected static ?string $navigationLabel = 'Pipeline Board';

    public $search = '';
    public $perPage = 25;
    public $priorityFilter = null;

    #[On('statusChangeEvent')]
    public function changeRecordStatus($id, $pipeline_stage_id): void
    {
        // Show loading state
        $this->dispatch('loading-started');

        try {
            $lead = Lead::find($id);
            $oldStage = $lead->pipelineStage;
            $lead->pipeline_stage_id = $pipeline_stage_id;
            $lead->save();
            $lead->load('pipelineStage');
            $lead->pipelineStageLogs()->create([
                'pipeline_stage_id' => $pipeline_stage_id,
                'notes' => null,
                'user_id' => auth()->id()
            ]);

            Notification::make()
                ->title('Stage Updated')
                ->body("{$lead->fullName()} moved from {$oldStage->name} to {$lead->pipelineStage->name}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body("Failed to update lead stage: {$e->getMessage()}")
                ->danger()
                ->send();
        } finally {
            $this->dispatch('loading-finished');
        }
    }

    public function refresh(): void
    {
        $this->resetPage();
    }

    protected function getViewData(): array
    {
        $statuses = $this->statuses();
        $records = $this->records();

        $statuses = $statuses->map(function ($status) use ($records) {
            $status['group'] = $this->getId();
            $status['kanbanRecordsId'] = "{$this->getId()}-{$status['id']}";
            $status['records'] = $records->filter(fn ($record) => $this->isRecordInStatus($record, $status));
            return $status;
        });

        return [
            'records' => $records,
            'statuses' => $statuses,
        ];
    }

    protected function statuses(): Collection
    {
        return PipelineStage::query()
            ->orderBy('position')
            ->get()
            ->map(function (PipelineStage $stage) {
                return [
                    'id' => $stage->id,
                    'title' => $stage->name,
                    'color' => $stage->color ?? '#6b7280', // Default gray
                ];
            });
    }

    protected function records(): Collection
    {
        return Lead::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->with(['pipelineStage'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (Lead $lead) {
                return [
                    'id' => $lead->id,
                    'title' => $lead->fullName(),
                    'description' => $lead->company_name,
                    'status' => $lead->pipeline_stage_id,
                    'priority' => $lead->priority,
                    'updated_at' => $lead->updated_at,
                ];
            });
    }

    protected function isRecordInStatus($record, $status): bool
    {
        return $record['status'] === $status['id'];
    }
}
