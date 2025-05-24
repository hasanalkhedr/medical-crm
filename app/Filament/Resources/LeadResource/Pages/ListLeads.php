<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use App\Models\PipelineStage;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Adding `all` as our first tab
        $tabs['all'] = Tab::make('All Leads')
            // We will add a badge to show how many leads are in this tab
            ->badge(Lead::count());

        if (!auth()->user()->isAdmin()) {
            $tabs['my'] = Tab::make('My Leads')
                ->badge(Lead::where('employee_id', auth()->id())->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('employee_id', auth()->id());
                });
        }

        // Load all Pipeline Stages
        $pipelineStages = PipelineStage::orderBy('position')->withCount('leads')->get();

        // Loop through each Pipeline Stage
        foreach ($pipelineStages as $pipelineStage) {
            // Add a tab for each Pipeline Stage
            // Array index is going to be used in the URL as a slug, so we transform the name into a slug
            $tabs[str($pipelineStage->name)->slug()->toString()] = Tab::make($pipelineStage->name)
                // We will add a badge to show how many leads are in this tab
                ->badge($pipelineStage->leads_count)
                // We will modify the query to only show leads in this Pipeline Stage
                ->modifyQueryUsing(function ($query) use ($pipelineStage) {
                    return $query->where('pipeline_stage_id', $pipelineStage->id);
                });
        }
        $tabs['archived'] = Tab::make('Archived')
            ->badge(Lead::onlyTrashed()->count())
            ->modifyQueryUsing(function ($query) {
                return $query->onlyTrashed();
            });
        return $tabs;
    }
}
