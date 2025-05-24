<x-filament-panels::page>
    <x-filament::card wire:ignore.self>
        <div class="space-y-4">
            <!-- Search and Filter Bar -->
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <x-filament::input.wrapper class="w-full sm:w-64">
                    <x-filament::input type="search" wire:model.live.debounce.500ms="search"
                        placeholder="Search leads..." icon="heroicon-m-magnifying-glass" />
                </x-filament::input.wrapper>

                <div class="flex gap-2 items-center">
                    <x-filament::badge color="gray" class="px-3 py-1.5">
                        Total: {{ count($records) }} leads
                    </x-filament::badge>
                    <x-filament::button wire:click="refresh" icon="heroicon-m-arrow-path" color="gray" size="sm"
                        class="shrink-0" />
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="w-full h-full flex space-x-4 rtl:space-x-reverse overflow-x-auto pb-4">
                @foreach ($statuses as $status)
                    <div class="h-full flex-1 min-w-[280px]">
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 flex flex-col h-full"
                            id="{{ $status['id'] }}">
                            <div
                                class="p-3 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full"
                                        style="background-color: {{ $status['color'] }}"></span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $status['title'] }}
                                    </span>
                                </div>
                                <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full">
                                    {{ count($status['records']) }}
                                </span>
                            </div>

                            <div id="{{ $status['kanbanRecordsId'] }}" data-status-id="{{ $status['id'] }}"
                                class="space-y-3 p-3 flex-1 overflow-y-auto min-h-[100px] transition-all duration-200">
                                @foreach ($status['records'] as $record)
                                    <div id="{{ $record['id'] }}"
                                        class="shadow-sm bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow cursor-grab active:cursor-grabbing"
                                        wire:key="record-{{ $record['id'] }}">
                                        <div class="flex justify-between items-start gap-2">
                                            <p class="font-medium text-gray-900 dark:text-white line-clamp-2">
                                                {{ $record['title'] }}
                                            </p>
                                            @if ($record['priority'])
                                                <span
                                                    class="text-xs px-2 py-1 rounded-full
                                                      {{ $record['priority'] === 'high'
                                                          ? 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200'
                                                          : ($record['priority'] === 'medium'
                                                              ? 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200'
                                                              : 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200') }}">
                                                    {{ ucfirst($record['priority']) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($record['description'])
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                                {{ $record['description'] }}
                                            </p>
                                        @endif
                                        <div
                                            class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>#{{ $record['id'] }}</span>
                                            <span>{{ $record['updated_at']->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                @if (count($status['records']) === 0)
                                    <div class="text-center p-4 text-gray-400 dark:text-gray-500 text-sm">
                                        No leads in this stage
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.flex
            class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm z-10 items-center justify-center">
            <x-filament::loading-indicator class="w-10 h-10 text-primary-500" />
        </div>

        <!-- Sortable.js Implementation -->
        <div wire:ignore>
            <script>
                document.addEventListener('livewire:init', () => {
                    // Define componentId at the highest scope
                    const componentId = @js($this->getId());

                    function initializeKanban() {
                        const statuses = @json($statuses);

                        statuses.forEach(status => {
                            const container = document.getElementById(status.kanbanRecordsId);

                            if (!container || container.sortable) return;

                            container.sortable = new Sortable(container, {
                                group: status.group,
                                animation: 150,
                                ghostClass: 'kanban-ghost',
                                chosenClass: 'kanban-chosen',
                                dragClass: 'kanban-dragging',
                                onStart: () => {
                                    document.body.style.cursor = 'grabbing';
                                },
                                onEnd: (evt) => {
                                    document.body.style.cursor = '';
                                    if (evt.from !== evt.to || evt.oldIndex !== evt.newIndex) {
                                        Livewire.dispatch('statusChangeEvent', {
                                            id: evt.item.id,
                                            pipeline_stage_id: evt.to.dataset.statusId
                                        });
                                    }
                                }
                            });
                        });
                    }

                    // Initial setup
                    initializeKanban();

                    // Reinitialize after Livewire updates
                    Livewire.hook('commit', ({ component, succeed }) => {
                        succeed(() => {
                            // Now componentId is available in this scope
                            if (component.id === componentId) {
                                // Destroy existing instances first
                                document.querySelectorAll('[id^="' + componentId + '-"]').forEach(el => {
                                    if (el.sortable) {
                                        el.sortable.destroy();
                                        el.sortable = null;
                                    }
                                });
                                setTimeout(initializeKanban, 50);
                            }
                        });
                    });
                });
            </script>

            <style>
                .kanban-ghost {
                    opacity: 0.5;
                    background: rgba(59, 130, 246, 0.1);
                    border: 1px solid rgb(59, 130, 246);
                }
                .kanban-chosen {
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
                .kanban-dragging {
                    opacity: 0.5;
                }
            </style>
        </div>
    </x-filament::card>
</x-filament-panels::page>
