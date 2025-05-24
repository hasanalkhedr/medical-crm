<?php

// namespace App\Filament\Resources;

// use Filament\Forms;
// use App\Models\Role;
// use App\Models\Task;
// use App\Models\User;
// use Filament\Infolists\Components\Grid;
// use Filament\Tables;
// use App\Models\Lead;
// use Filament\Forms\Get;
// use Filament\Forms\Form;
// use Filament\Tables\Table;
// use App\Models\CustomField;
// use App\Models\PipelineStage;
// use Filament\Infolists\Infolist;
// use Filament\Resources\Resource;
// use Filament\Support\Colors\Color;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\Repeater;
// use Filament\Forms\Components\Textarea;
// use Filament\Infolists\Components\Tabs;
// use Filament\Tables\Columns\TextColumn;
// use Illuminate\Support\Facades\Storage;
// use Filament\Forms\Components\TextInput;
// use Filament\Notifications\Notification;
// use Filament\Forms\Components\FileUpload;
// use Illuminate\Database\Eloquent\Builder;
// use Filament\Infolists\Components\Section;
// use Filament\Tables\Filters\TrashedFilter;
// use Filament\Infolists\Components\TextEntry;
// use Filament\Infolists\Components\ViewEntry;
// use Filament\Infolists\Components\Actions\Action;
// use App\Filament\Resources\LeadResource\Pages;
// use Filament\Infolists\Components\RepeatableEntry;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\QuoteResource\Pages\CreateQuote;
// use App\Filament\Resources\LeadResource\RelationManagers;

// class LeadResource extends Resource
// {
//     protected static ?string $model = Lead::class;

//     protected static ?string $navigationIcon = 'heroicon-o-user';

//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 // Employee Information Section
//                 Forms\Components\Section::make('Employee Information')
//                     ->schema([
//                         Forms\Components\Select::make('employee_id')
//                             ->options(User::all()->pluck('name', 'id'))
//                             ->label('Assigned To')
//                             ->prefixIcon('heroicon-o-user-circle')
//                     ])
//                     ->hidden(!auth()->user()->isAdmin())
//                     ->columns(1)
//                     ->collapsible()
//                     ->columnSpan(['lg' => 1]),

//                 // Main Content Grid
//                 Forms\Components\Grid::make()
//                     ->schema([
//                         // Left Column
//                         Forms\Components\Group::make()
//                             ->schema([
//                                 // Basic Information
//                                 Forms\Components\Section::make('Basic Information')
//                                     ->schema([
//                                         Forms\Components\TextInput::make('first_name')
//                                             ->required()
//                                             ->maxLength(255)
//                                             ->columnSpan(['md' => 1]),
//                                         Forms\Components\TextInput::make('last_name')
//                                             ->required()
//                                             ->maxLength(255)
//                                             ->columnSpan(['md' => 1]),
//                                         Forms\Components\TextInput::make('email')
//                                             ->email()
//                                             ->maxLength(255)
//                                             ->columnSpan(['md' => 1]),
//                                         Forms\Components\TextInput::make('phone_number')
//                                             ->tel()
//                                             ->maxLength(255)
//                                             ->columnSpan(['md' => 1]),
//                                         Forms\Components\Textarea::make('description')
//                                             ->maxLength(65535)
//                                             ->columnSpanFull(),
//                                     ])
//                                     ->columns(2)
//                                     ->collapsible(),

//                                 // Medical Profile - Now with better visual hierarchy
//                                 Forms\Components\Section::make('Medical Profile')
//                                     ->schema([
//                                         Forms\Components\Grid::make()
//                                             ->schema([
//                                                 Forms\Components\Select::make('medicalProfile.blood_type')
//                                                     ->options([
//                                                         'A+' => 'A+',
//                                                         'A-' => 'A-',
//                                                         'B+' => 'B+',
//                                                         'B-' => 'B-',
//                                                         'AB+' => 'AB+',
//                                                         'AB-' => 'AB-',
//                                                         'O+' => 'O+',
//                                                         'O-' => 'O-',
//                                                     ])
//                                                     ->label('Blood Type')
//                                                     ->selectablePlaceholder(false)
//                                                     ->native(false)
//                                                     ->columnSpan(['md' => 1]),

//                                                 Forms\Components\TextInput::make('medicalProfile.height')
//                                                     ->numeric()
//                                                     ->suffix('cm')
//                                                     ->columnSpan(['md' => 1]),

//                                                 Forms\Components\TextInput::make('medicalProfile.weight')
//                                                     ->numeric()
//                                                     ->suffix('kg')
//                                                     ->columnSpan(['md' => 1]),
//                                             ])
//                                             ->columns(3),

//                                         Forms\Components\Fieldset::make('Medical History')
//                                             ->schema([
//                                                 Forms\Components\Textarea::make('medicalProfile.known_allergies')
//                                                     ->label('Known Allergies')
//                                                     ->placeholder('List any known allergies')
//                                                     ->rows(3)
//                                                     ->columnSpanFull(),

//                                                 Forms\Components\Textarea::make('medicalProfile.chronic_conditions')
//                                                     ->label('Chronic Conditions')
//                                                     ->placeholder('Any ongoing medical conditions')
//                                                     ->rows(3)
//                                                     ->columnSpanFull(),

//                                                 Forms\Components\Textarea::make('medicalProfile.current_medications')
//                                                     ->label('Current Medications')
//                                                     ->placeholder('List all current medications')
//                                                     ->rows(3)
//                                                     ->columnSpanFull(),
//                                             ])
//                                             ->columns(1)
//                                     ])
//                                     ->collapsible()
//                                     ->columnSpanFull(),
//                             ])
//                             ->columnSpan(['lg' => 2]),

//                         // Right Column
//                         Forms\Components\Group::make()
//                             ->schema([
//                                 // Classification
//                                 Forms\Components\Section::make('Classification')
//                                     ->schema([
//                                         Forms\Components\Select::make('lead_source_id')
//                                             ->relationship('leadSource', 'name')
//                                             ->label('Source')
//                                             ->prefixIcon('heroicon-o-arrow-down-on-square'),

//                                         Forms\Components\Select::make('tags')
//                                             ->relationship('tags', 'name')
//                                             ->multiple()
//                                             ->label('Tags')
//                                             ->prefixIcon('heroicon-o-tag'),

//                                         Forms\Components\Select::make('pipeline_stage_id')
//                                             ->relationship('pipelineStage', 'name')
//                                             ->label('Stage')
//                                             ->default(PipelineStage::where('is_default', true)->first()?->id)
//                                             ->prefixIcon('heroicon-o-queue-list'),
//                                     ])
//                                     ->columns(1)
//                                     ->collapsible(),

//                                 // Documents
//                                 Forms\Components\Section::make('Documents')
//                                     ->visibleOn('edit')
//                                     ->schema([
//                                         Forms\Components\Repeater::make('documents')
//                                             ->relationship()
//                                             ->label('')
//                                             ->reorderable(false)
//                                             ->addActionLabel('Add Document')
//                                             ->itemLabel(fn(array $state): ?string => $state['file_path'] ?? null)
//                                             ->schema([
//                                                 Forms\Components\FileUpload::make('file_path')
//                                                     ->required()
//                                                     ->acceptedFileTypes(['application/pdf', 'image/*'])
//                                                     ->downloadable()
//                                                     ->directory('lead-documents')
//                                                     ->openable(),
//                                                 Forms\Components\Textarea::make('comments')
//                                                     ->rows(2),
//                                             ])
//                                             ->grid(1)
//                                     ])
//                                     ->collapsible(),
//                             ])
//                             ->columnSpan(['lg' => 1]),
//                     ])
//                     ->columns(3),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('employee.name')
//                     ->label('Assigned To')
//                     ->hidden(!auth()->user()->isAdmin())
//                     ->sortable()
//                     ->searchable()
//                     ->description(fn(Lead $record): string => $record->pipelineStage->name ?? '')
//                     ->wrap(),

//                 Tables\Columns\TextColumn::make('first_name')
//                     ->label('Name')
//                     ->formatStateUsing(fn($record) => "
//                     <div class='flex items-center gap-2'>
//                         <div class='h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center'>
//                             <span class='text-sm font-medium text-gray-600'>" . substr($record->first_name, 0, 1) . "</span>
//                         </div>
//                         <div>
//                             <p class='font-medium text-gray-900'>{$record->first_name} {$record->last_name}</p>
//                             <p class='text-xs text-gray-500'>{$record->email}</p>
//                         </div>
//                     </div>
//                 ")->html()
//                     ->searchable(['first_name', 'last_name', 'email']),

//                 Tables\Columns\TextColumn::make('phone_number')
//                     ->label('Contact')
//                     ->searchable()
//                     ->icon('heroicon-o-phone')
//                     ->color('gray'),

//                 Tables\Columns\TextColumn::make('medicalProfile.blood_type')
//                     ->label('Blood Type')
//                     ->badge()
//                     ->color(fn($state) => match ($state) {
//                         'A+' => 'danger',
//                         'A-' => 'danger',
//                         'B+' => 'warning',
//                         'B-' => 'warning',
//                         'AB+' => 'success',
//                         'AB-' => 'success',
//                         'O+' => 'primary',
//                         'O-' => 'primary',
//                         default => 'gray',
//                     })
//                     ->icon(fn($state) => $state ? 'heroicon-o-heart' : null),

//                 Tables\Columns\TextColumn::make('leadSource.name')
//                     ->label('Source')
//                     ->badge()
//                     ->color('gray'),

//                 Tables\Columns\TextColumn::make('created_at')
//                     ->label('Created')
//                     ->dateTime('M d, Y')
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->filters([
//                 Tables\Filters\SelectFilter::make('pipeline_stage_id')
//                     ->relationship('pipelineStage', 'name')
//                     ->label('Stage')
//                     ->indicator('Stage'),

//                 Tables\Filters\SelectFilter::make('lead_source_id')
//                     ->relationship('leadSource', 'name')
//                     ->label('Source')
//                     ->indicator('Source'),

//                 Tables\Filters\SelectFilter::make('employee_id')
//                     ->relationship('employee', 'name')
//                     ->label('Assigned To')
//                     ->indicator('Assigned To'),

//                 Tables\Filters\TrashedFilter::make(),
//             ])
//             ->actions([
//                 Tables\Actions\ActionGroup::make([
//                     Tables\Actions\ViewAction::make()
//                         ->icon('heroicon-o-eye'),

//                     Tables\Actions\EditAction::make()
//                         ->icon('heroicon-o-pencil-square')
//                         ->hidden(fn($record) => $record->trashed()),

//                     Tables\Actions\DeleteAction::make()
//                         ->icon('heroicon-o-trash'),

//                     Tables\Actions\RestoreAction::make()
//                         ->icon('heroicon-o-arrow-uturn-left'),

//                     Tables\Actions\Action::make('Move to Stage')
//                         ->icon('heroicon-o-arrow-right-circle')
//                         ->hidden(fn($record) => $record->trashed())
//                         ->form([
//                             Forms\Components\Select::make('pipeline_stage_id')
//                                 ->options(PipelineStage::pluck('name', 'id')->toArray())
//                                 ->default(function (Lead $record) {
//                                     $currentPosition = $record->pipelineStage->position;
//                                     return PipelineStage::where('position', '>', $currentPosition)->first()?->id;
//                                 }),
//                             Forms\Components\Textarea::make('notes')
//                         ])
//                         ->action(function (Lead $lead, array $data): void {
//                             $lead->pipeline_stage_id = $data['pipeline_stage_id'];
//                             $lead->save();

//                             $lead->pipelineStageLogs()->create([
//                                 'pipeline_stage_id' => $data['pipeline_stage_id'],
//                                 'notes' => $data['notes'],
//                                 'user_id' => auth()->id()
//                             ]);

//                             Notification::make()
//                                 ->title('Pipeline stage updated')
//                                 ->success()
//                                 ->send();
//                         }),
//                 ])
//                     ->tooltip('Actions')
//                     ->icon('heroicon-o-ellipsis-vertical')
//                     ->color('gray'),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                     Tables\Actions\ForceDeleteBulkAction::make(),
//                     Tables\Actions\RestoreBulkAction::make(),
//                 ]),
//             ])
//             ->recordUrl(function ($record) {
//                 return $record->trashed() ? null : Pages\ViewLead::getUrl([$record->id]);
//             })
//             ->defaultSort('created_at', 'desc')
//             ->groups([
//                 Tables\Grouping\Group::make('pipeline_stage.name')
//                     ->label('Pipeline Stage')
//                     ->collapsible(),

//                 Tables\Grouping\Group::make('created_at')
//                     ->label('Created Date')
//                     ->date()
//                     ->collapsible(),
//             ])
//             ->groupsInDropdownOnDesktop()
//             ->groupRecordsTriggerAction(
//                 fn(Tables\Actions\Action $action) => $action
//                     ->button()
//                     ->label('Group records'),
//             );
//     }

//     public static function infoList(Infolist $infolist): Infolist
//     {
//         return $infolist
//             ->schema([
//                 // Main Grid Layout
//                 Section::make('')
//                     ->schema([
//                         // Left Column
//                         Section::make('Patient Information')
//                             ->schema([
//                                 TextEntry::make('full_name')
//                                     ->label('')
//                                     ->formatStateUsing(fn($record) => "
//                                     <div class='flex items-center gap-4 mb-4'>
//                                         <div class='h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center'>
//                                             <span class='text-xl font-medium text-primary-600'>"
//                                         . substr($record->first_name, 0, 1) . substr($record->last_name, 0, 1) .
//                                         "</span>
//                                         </div>
//                                         <div>
//                                             <h2 class='text-2xl font-bold text-gray-900'>{$record->first_name} {$record->last_name}</h2>
//                                             <p class='text-sm text-gray-500'>{$record->email}</p>
//                                         </div>
//                                     </div>
//                                 ")
//                                     ->html(),

//                                 TextEntry::make('phone_number')
//                                     ->label('')
//                                     ->formatStateUsing(fn($state) => "
//                                     <div class='flex items-center gap-2 text-gray-700 mb-2'>
//                                         <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
//                                             <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' />
//                                         </svg>
//                                         <span class='text-lg'>{$state}</span>
//                                     </div>
//                                 ")
//                                     ->html(),

//                                 TextEntry::make('leadSource.name')
//                                     ->label('Source')
//                                     ->formatStateUsing(fn($state) => "
//                                     <div class='inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800'>
//                                         {$state}
//                                     </div>
//                                 ")
//                                     ->html(),
//                             ])
//                             ->columnSpan(['lg' => 1]),

//                         // Right Column - Medical Profile
//                         Section::make('Medical Profile')
//                             ->schema([
//                                 // Vital Stats
//                                 Section::make('Vital Statistics')
//                                     ->schema([
//                                         Grid::make(3)
//                                             ->schema([
//                                                 TextEntry::make('medicalProfile.blood_type')
//                                                     ->label('Blood Type')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
//                                                         <span class='text-xs font-medium text-gray-500'>Blood Type</span>
//                                                         <span class='mt-1 text-xl font-bold text-" . self::getBloodTypeColor($state) . "-600'>{$state}</span>
//                                                     </div>
//                                                 " : '')
//                                                     ->html(),

//                                                 TextEntry::make('medicalProfile.height')
//                                                     ->label('Height')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
//                                                         <span class='text-xs font-medium text-gray-500'>Height</span>
//                                                         <span class='mt-1 text-xl font-bold text-blue-600'>{$state} cm</span>
//                                                     </div>
//                                                 " : '')
//                                                     ->html(),

//                                                 TextEntry::make('medicalProfile.weight')
//                                                     ->label('Weight')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
//                                                         <span class='text-xs font-medium text-gray-500'>Weight</span>
//                                                         <span class='mt-1 text-xl font-bold text-blue-600'>{$state} kg</span>
//                                                     </div>
//                                                 " : '')
//                                                     ->html(),
//                                             ]),
//                                     ])
//                                     ->columnSpanFull(),

//                                 // Medical History Cards
//                                 Grid::make(2)
//                                     ->schema([
//                                         Section::make('Known Allergies')
//                                             ->schema([
//                                                 TextEntry::make('medicalProfile.known_allergies')
//                                                     ->label('')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='p-4 bg-white rounded-lg shadow'>
//                                                         <div class='flex items-center gap-2 mb-2'>
//                                                             <svg class='w-5 h-5 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
//                                                                 <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' />
//                                                             </svg>
//                                                             <h3 class='font-medium text-gray-900'>Known Allergies</h3>
//                                                         </div>
//                                                         <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
//                                                     </div>
//                                                 " : '<div class="p-4 text-gray-400">No allergies recorded</div>')
//                                                     ->html(),
//                                             ]),

//                                         Section::make('Chronic Conditions')
//                                             ->schema([
//                                                 TextEntry::make('medicalProfile.chronic_conditions')
//                                                     ->label('')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='p-4 bg-white rounded-lg shadow'>
//                                                         <div class='flex items-center gap-2 mb-2'>
//                                                             <svg class='w-5 h-5 text-yellow-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
//                                                                 <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' />
//                                                             </svg>
//                                                             <h3 class='font-medium text-gray-900'>Chronic Conditions</h3>
//                                                         </div>
//                                                         <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
//                                                     </div>
//                                                 " : '<div class="p-4 text-gray-400">No chronic conditions recorded</div>')
//                                                     ->html(),
//                                             ]),

//                                         Section::make('Current Medications')
//                                             ->schema([
//                                                 TextEntry::make('medicalProfile.current_medications')
//                                                     ->label('')
//                                                     ->formatStateUsing(fn($state) => $state ? "
//                                                     <div class='p-4 bg-white rounded-lg shadow'>
//                                                         <div class='flex items-center gap-2 mb-2'>
//                                                             <svg class='w-5 h-5 text-green-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
//                                                                 <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />
//                                                             </svg>
//                                                             <h3 class='font-medium text-gray-900'>Current Medications</h3>
//                                                         </div>
//                                                         <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
//                                                     </div>
//                                                 " : '<div class="p-4 text-gray-400">No medications recorded</div>')
//                                                     ->html(),
//                                             ]),
//                                     ])
//                                     ->columnSpanFull(),
//                             ])
//                             ->columnSpan(['lg' => 2]),
//                     ])
//                     ->columns(3),

//                 // Documents Section
//                 Section::make('Documents')
//                     ->hidden(fn($record) => $record->documents->isEmpty())
//                     ->schema([
//                         RepeatableEntry::make('documents')
//                             ->label('')
//                             ->schema([
//                                 Grid::make(2)
//                                     ->schema([
//                                         TextEntry::make('file_path')
//                                             ->label('')
//                                             ->formatStateUsing(fn($state) => "
//                                             <div class='flex items-center gap-3 p-3 bg-white rounded-lg shadow hover:bg-gray-50'>
//                                                 <svg class='w-8 h-8 text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
//                                                     <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z' />
//                                                 </svg>
//                                                 <div>
//                                                     <p class='font-medium text-gray-900'>" . basename($state) . "</p>
//                                                     <p class='text-sm text-gray-500'>" . Storage::size($state) . " KB</p>
//                                                 </div>
//                                             </div>
//                                         ")
//                                             ->html(),

//                                         TextEntry::make('comments')
//                                             ->label('')
//                                             ->formatStateUsing(fn($state) => $state ? "
//                                             <div class='p-3 bg-gray-50 rounded-lg'>
//                                                 <p class='text-gray-700'>{$state}</p>
//                                             </div>
//                                         " : '')
//                                             ->html(),
//                                     ]),
//                             ])
//                             ->grid(1),
//                     ]),
//             ]);
//     }
//     protected static function getBloodTypeColor(string $bloodType): string
//     {
//         return match ($bloodType) {
//             'A+' => 'red',
//             'A-' => 'red',
//             'B+' => 'yellow',
//             'B-' => 'yellow',
//             'AB+' => 'green',
//             'AB-' => 'green',
//             'O+' => 'blue',
//             'O-' => 'blue',
//             default => 'gray',
//         };
//     }

//     public static function getRelations(): array
//     {
//         return [
//             // Add relation managers here if needed
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListLeads::route('/'),
//             'create' => Pages\CreateLead::route('/create'),
//             'edit' => Pages\EditLead::route('/{record}/edit'),
//             'view' => Pages\ViewLead::route('/{record}'),
//         ];
//     }

//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()
//             ->withoutGlobalScopes([
//                 SoftDeletingScope::class,
//             ]);
//     }
// }

namespace App\Filament\Resources;

use App\Services\CountryCodes;
use Closure;
use Filament\Forms;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Filament\Infolists\Components\Grid;
use Filament\Tables;
use App\Models\Lead;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomField;
use App\Models\PipelineStage;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Actions\Action;
use App\Filament\Resources\LeadResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuoteResource\Pages\CreateQuote;
use App\Filament\Resources\LeadResource\RelationManagers;
use Giggsey\Locale\Locale;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Intl\Countries;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Employee Information Section
                Forms\Components\Section::make('Employee Information')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->options(function () {
                                return User::query()
                                    ->whereNotNull('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->label('Assigned To')
                            ->prefixIcon('heroicon-o-user-circle')
                    ])
                    ->hidden(!auth()->user()->isAdmin())
                    ->columns(1)
                    ->collapsible()
                    ->columnSpan(['lg' => 1]),

                // Main Content Grid
                Forms\Components\Grid::make()
                    ->schema([
                        // Left Column
                        Forms\Components\Group::make()
                            ->schema([
                                // Basic Information
                                Forms\Components\Section::make('Basic Information')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('first_name')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('last_name')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\Select::make('gender')
                                                    ->options([
                                                        'male' => 'Male',
                                                        'female' => 'Female',
                                                        'other' => 'Other',
                                                        'unknown' => 'Prefer not to say',
                                                    ])
                                                    ->native(false)
                                            ]),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('date_of_birth')
                                                    ->maxDate(now())
                                                    ->displayFormat('M d, Y'),

                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Select::make('phone_country_code')
                                                            ->label('Country')
                                                            ->options(CountryCodes::getCountriesWithCodes())
                                                            ->searchable()
                                                            ->required()
                                                            ->live()
                                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                                if ($state) {
                                                                    $phoneUtil = PhoneNumberUtil::getInstance();
                                                                    $countryCode = $phoneUtil->getCountryCodeForRegion($state);
                                                                    $set('phone_number', '+' . $countryCode);
                                                                } else {
                                                                    $set('phone_number', '');
                                                                }
                                                            })
                                                            ->prefixIcon('heroicon-o-globe-alt'),

                                                        Forms\Components\TextInput::make('phone_number')
                                                            ->label('Phone Number')
                                                            ->tel()
                                                            ->required()
                                                            ->rules([
                                                                function (Forms\Get $get) {
                                                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                                                        $countryCode = $get('phone_country_code');
                                                                        if (!$countryCode) {
                                                                            $fail('Please select a country first.');
                                                                            return;
                                                                        }

                                                                        $phoneUtil = PhoneNumberUtil::getInstance();
                                                                        try {
                                                                            $phoneNumber = $phoneUtil->parse($value, $countryCode);
                                                                            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                                                                                $exampleNumber = $phoneUtil->getExampleNumber($countryCode);
                                                                                $exampleFormatted = $phoneUtil->format(
                                                                                    $exampleNumber,
                                                                                    PhoneNumberFormat::INTERNATIONAL
                                                                                );
                                                                                $fail("Invalid phone number format. Example: {$exampleFormatted}");
                                                                            }
                                                                        } catch (\Exception $e) {
                                                                            $fail('Invalid phone number format.');
                                                                        }
                                                                    };
                                                                },
                                                            ])
                                                            ->prefixIcon('heroicon-o-phone'),
                                                    ]),

                                            ]),

                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('description')
                                            ->maxLength(65535)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->collapsible(),

                                // Emergency Contact Information
                                Forms\Components\Section::make('Emergency Contact')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('emergency_contact_name')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('emergency_contact_phone')
                                                    ->tel()
                                                    ->maxLength(255),
                                            ]),
                                    ])
                                    ->collapsible(),

                                // Insurance Information
                                Forms\Components\Section::make('Insurance Information')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('insurance_provider')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('insurance_policy_number')
                                                    ->maxLength(255),
                                            ]),
                                    ])
                                    ->collapsible(),

                                // Medical Profile
                                Forms\Components\Section::make('Medical Profile')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Select::make('medicalProfile.blood_type')
                                                    ->options([
                                                        'A+' => 'A+',
                                                        'A-' => 'A-',
                                                        'B+' => 'B+',
                                                        'B-' => 'B-',
                                                        'AB+' => 'AB+',
                                                        'AB-' => 'AB-',
                                                        'O+' => 'O+',
                                                        'O-' => 'O-',
                                                    ])
                                                    ->label('Blood Type')
                                                    ->selectablePlaceholder(false)
                                                    ->native(false)
                                                    ->columnSpan(['md' => 1]),

                                                Forms\Components\TextInput::make('medicalProfile.height')
                                                    ->numeric()
                                                    ->suffix('cm')
                                                    ->columnSpan(['md' => 1]),

                                                Forms\Components\TextInput::make('medicalProfile.weight')
                                                    ->numeric()
                                                    ->suffix('kg')
                                                    ->columnSpan(['md' => 1]),
                                            ])
                                            ->columns(3),

                                        Forms\Components\Fieldset::make('Medical History')
                                            ->schema([
                                                Forms\Components\Textarea::make('medicalProfile.known_allergies')
                                                    ->label('Known Allergies')
                                                    ->placeholder('List any known allergies')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Textarea::make('medicalProfile.chronic_conditions')
                                                    ->label('Chronic Conditions')
                                                    ->placeholder('Any ongoing medical conditions')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Textarea::make('medicalProfile.current_medications')
                                                    ->label('Current Medications')
                                                    ->placeholder('List all current medications')
                                                    ->rows(3)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(1)
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(['lg' => 2]),

                        // Right Column
                        Forms\Components\Group::make()
                            ->schema([
                                // Classification
                                Forms\Components\Section::make('Classification')
                                    ->schema([
                                        Forms\Components\Select::make('lead_source_id')
                                            ->relationship('leadSource', 'name')
                                            ->label('Source')
                                            ->prefixIcon('heroicon-o-arrow-down-on-square'),

                                        Forms\Components\Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->label('Tags')
                                            ->prefixIcon('heroicon-o-tag'),

                                        Forms\Components\Select::make('pipeline_stage_id')
                                            ->relationship('pipelineStage', 'name')
                                            ->label('Stage')
                                            ->default(function () {
                                                return PipelineStage::where('is_default', true)->first()?->id;
                                            })
                                            ->prefixIcon('heroicon-o-queue-list')
                                            ->options(function () {
                                                $options = PipelineStage::pluck('name', 'id')->toArray();
                                                return array_filter($options); // Remove any null values
                                            })
                                            ->rules([
                                                function (Lead $record) {
                                                    return function (string $attribute, $value, Closure $fail) use ($record) {
                                                        if ($record->exists) {
                                                            $newStage = PipelineStage::find($value);
                                                            $currentStage = $record->pipelineStage;

                                                            if ($currentStage && $newStage && $newStage->position < $currentStage->position) {
                                                                $fail("Cannot move to a previous stage in the pipeline.");
                                                            }
                                                        }
                                                    };
                                                },
                                            ]),
                                    ])
                                    ->columns(1)
                                    ->collapsible(),

                                // Documents
                                Forms\Components\Section::make('Documents')
                                    ->visibleOn('edit')
                                    ->schema([
                                        Forms\Components\Repeater::make('documents')
                                            ->relationship()
                                            ->label('')
                                            ->reorderable(false)
                                            ->addActionLabel('Add Document')
                                            ->itemLabel(fn(array $state): ?string => $state['file_path'] ?? null)
                                            ->schema([
                                                Forms\Components\FileUpload::make('file_path')
                                                    ->required()
                                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                                    ->downloadable()
                                                    ->directory('lead-documents')
                                                    ->openable(),
                                                Forms\Components\Textarea::make('comments')
                                                    ->rows(2),
                                            ])
                                            ->grid(1)
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Assigned To')
                    ->hidden(!auth()->user()->isAdmin())
                    ->sortable()
                    ->searchable()
                    ->description(fn(Lead $record): string => $record->pipelineStage->name ?? '')
                    ->wrap(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('Patient')
                    ->formatStateUsing(fn($record) => "
                    <div class='flex items-center gap-2'>
                        <div class='h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center'>
                            <span class='text-sm font-medium text-gray-600'>" . substr($record->first_name, 0, 1) . "</span>
                        </div>
                        <div>
                            <p class='font-medium text-gray-900'>{$record->first_name} {$record->last_name}</p>
                            <p class='text-xs text-gray-500'>{$record->date_of_birth?->format('M d, Y')} • {$record->gender}</p>
                        </div>
                    </div>
                ")->html()
                    ->searchable(['first_name', 'last_name', 'email']),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Contact')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('formatted_phone_number')
                    ->label('Phone')
                    ->searchable(['phone_number'])
                    ->icon('heroicon-o-phone')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('medicalProfile.blood_type')
                    ->label('Blood Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'A+' => 'danger',
                        'A-' => 'danger',
                        'B+' => 'warning',
                        'B-' => 'warning',
                        'AB+' => 'success',
                        'AB-' => 'success',
                        'O+' => 'primary',
                        'O-' => 'primary',
                        default => 'gray',
                    })
                    ->icon(fn($state) => $state ? 'heroicon-o-heart' : null),

                Tables\Columns\TextColumn::make('leadSource.name')
                    ->label('Source')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('insurance_provider')
                    ->label('Insurance')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('emergency_contact_name')
                    ->label('Emergency Contact')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pipeline_stage_id')
                    ->label('Stage')
                    ->options(function () {
                        return PipelineStage::query()
                            ->whereNotNull('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->indicator('Stage'),

                Tables\Filters\SelectFilter::make('lead_source_id')
                    ->label('Source')
                    ->options(function () {
                        return \App\Models\LeadSource::query()
                            ->whereNotNull('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->indicator('Source'),

                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Assigned To')
                    ->options(function () {
                        return User::query()
                            ->whereNotNull('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->indicator('Assigned To'),

                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                        'unknown' => 'Prefer not to say',
                    ])
                    ->label('Gender')
                    ->indicator('Gender'),

                Tables\Filters\SelectFilter::make('insurance_provider')
                    ->options(function () {
                        return Lead::query()
                            ->select('insurance_provider')
                            ->distinct()
                            ->whereNotNull('insurance_provider')
                            ->pluck('insurance_provider', 'insurance_provider')
                            ->toArray();
                    })
                    ->label('Insurance Provider')
                    ->indicator('Insurance Provider'),

                Tables\Filters\TrashedFilter::make(),
                // Tables\Filters\SelectFilter::make('pipeline_stage_id')
                //     ->relationship('pipelineStage', 'name')
                //     ->label('Stage')
                //     ->indicator('Stage'),

                // Tables\Filters\SelectFilter::make('lead_source_id')
                //     ->relationship('leadSource', 'name')
                //     ->label('Source')
                //     ->indicator('Source'),

                // Tables\Filters\SelectFilter::make('employee_id')
                //     ->relationship('employee', 'name')
                //     ->label('Assigned To')
                //     ->indicator('Assigned To'),

                // Tables\Filters\SelectFilter::make('gender')
                //     ->options([
                //         'male' => 'Male',
                //         'female' => 'Female',
                //         'other' => 'Other',
                //         'unknown' => 'Prefer not to say',
                //     ])
                //     ->label('Gender')
                //     ->indicator('Gender'),

                // Tables\Filters\SelectFilter::make('insurance_provider')
                //     ->options(fn() => Lead::query()->distinct('insurance_provider')->pluck('insurance_provider', 'insurance_provider'))
                //     ->label('Insurance Provider')
                //     ->indicator('Insurance Provider'),

                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil-square')
                        ->hidden(fn($record) => $record->trashed()),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),

                    Tables\Actions\RestoreAction::make()
                        ->icon('heroicon-o-arrow-uturn-left'),

                    Tables\Actions\Action::make('Move to Stage')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->hidden(fn($record) => $record->trashed())
                        ->form([
                            Forms\Components\Select::make('pipeline_stage_id')
                                ->relationship('pipelineStage', 'name')
                                ->label('Stage')
                                ->default(function () {
                                    return PipelineStage::where('is_default', true)->first()?->id;
                                })
                                ->prefixIcon('heroicon-o-queue-list')
                                ->options(function () {
                                    $options = PipelineStage::pluck('name', 'id')->toArray();
                                    return array_filter($options); // Remove any null values
                                })
                                ->rules([
                                    function (Lead $record) {
                                        return function (string $attribute, $value, Closure $fail) use ($record) {
                                            if ($record->exists) {
                                                $newStage = PipelineStage::find($value);
                                                $currentStage = $record->pipelineStage;

                                                if ($currentStage && $newStage && $newStage->position < $currentStage->position) {
                                                    $fail("Cannot move to a previous stage in the pipeline.");
                                                }
                                            }
                                        };
                                    },
                                ]),
                            Forms\Components\Textarea::make('notes')
                        ])
                        ->action(function (Lead $lead, array $data): void {
                            $lead->pipeline_stage_id = $data['pipeline_stage_id'];
                            $lead->save();

                            $lead->pipelineStageLogs()->create([
                                'pipeline_stage_id' => $data['pipeline_stage_id'],
                                'notes' => $data['notes'],
                                'user_id' => auth()->id()
                            ]);

                            Notification::make()
                                ->title('Pipeline stage updated')
                                ->success()
                                ->send();
                        }),
                ])
                    ->tooltip('Actions')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(function ($record) {
                return $record->trashed() ? null : Pages\ViewLead::getUrl([$record->id]);
            })
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('pipeline_stage.name')
                    ->label('Pipeline Stage')
                    ->collapsible(),

                Tables\Grouping\Group::make('created_at')
                    ->label('Created Date')
                    ->date()
                    ->collapsible(),

                Tables\Grouping\Group::make('insurance_provider')
                    ->label('Insurance Provider')
                    ->collapsible(),
            ])
            ->groupsInDropdownOnDesktop()
            ->groupRecordsTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Group records'),
            );
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Main Grid Layout
                Section::make('')
                    ->schema([
                        // Left Column
                        Section::make('Patient Information')
                            ->schema([
                                TextEntry::make('first_name')
                                    ->label('')
                                    ->formatStateUsing(fn($record) => "
                                        <div class='flex items-center gap-4 mb-4'>
                                            <div class='h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center'>
                                                <span class='text-xl font-medium text-primary-600'>"
                                        . substr($record->first_name, 0, 1) . substr($record->last_name, 0, 1) .
                                        "</span>
                                            </div>
                                            <div>
                                                <h2 class='text-2xl font-bold text-gray-900'>{$record->first_name} {$record->last_name}</h2>
                                                <p class='text-sm text-gray-500'>{$record->email}</p>
                                            </div>
                                        </div>
                                    ")
                                    ->html(),

                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('date_of_birth')
                                            ->label('Date of Birth')
                                            ->date('M d, Y')
                                            ->icon('heroicon-o-cake'),

                                        TextEntry::make('gender')
                                            ->label('Gender')
                                            ->icon('heroicon-o-user'),

                                        TextEntry::make('phone_number')
                                            ->label('Phone')
                                            ->icon('heroicon-o-phone'),
                                        TextEntry::make('formatted_phone_number')
                                            ->label('Phone')
                                            ->icon('heroicon-o-phone'),
                                        TextEntry::make('leadSource.name')
                                            ->label('Source')
                                            ->icon('heroicon-o-arrow-down-on-square'),
                                    ]),

                                Section::make('Emergency Contact')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('emergency_contact_name')
                                                    ->label('Name')
                                                    ->icon('heroicon-o-user-circle'),

                                                TextEntry::make('emergency_contact_phone')
                                                    ->label('Phone')
                                                    ->icon('heroicon-o-phone'),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make('Insurance Information')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('insurance_provider')
                                                    ->label('Provider')
                                                    ->icon('heroicon-o-shield-check'),

                                                TextEntry::make('insurance_policy_number')
                                                    ->label('Policy Number')
                                                    ->icon('heroicon-o-document-text'),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpan(['lg' => 1]),

                        // Right Column - Medical Profile
                        Section::make('Medical Profile')
                            ->schema([
                                // Vital Stats
                                Section::make('Vital Statistics')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextEntry::make('medicalProfile.blood_type')
                                                    ->label('Blood Type')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
                                                            <span class='text-xs font-medium text-gray-500'>Blood Type</span>
                                                            <span class='mt-1 text-xl font-bold text-" . self::getBloodTypeColor($state) . "-600'>{$state}</span>
                                                        </div>
                                                    " : '')
                                                    ->html(),

                                                TextEntry::make('medicalProfile.height')
                                                    ->label('Height')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
                                                            <span class='text-xs font-medium text-gray-500'>Height</span>
                                                            <span class='mt-1 text-xl font-bold text-blue-600'>{$state} cm</span>
                                                        </div>
                                                    " : '')
                                                    ->html(),

                                                TextEntry::make('medicalProfile.weight')
                                                    ->label('Weight')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='flex flex-col items-center p-3 bg-white rounded-lg shadow'>
                                                            <span class='text-xs font-medium text-gray-500'>Weight</span>
                                                            <span class='mt-1 text-xl font-bold text-blue-600'>{$state} kg</span>
                                                        </div>
                                                    " : '')
                                                    ->html(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),

                                // Medical History Cards
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Known Allergies')
                                            ->schema([
                                                TextEntry::make('medicalProfile.known_allergies')
                                                    ->label('')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='p-4 bg-white rounded-lg shadow'>
                                                            <div class='flex items-center gap-2 mb-2'>
                                                                <svg class='w-5 h-5 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' />
                                                                </svg>
                                                                <h3 class='font-medium text-gray-900'>Known Allergies</h3>
                                                            </div>
                                                            <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
                                                        </div>
                                                    " : '<div class="p-4 text-gray-400">No allergies recorded</div>')
                                                    ->html(),
                                            ]),

                                        Section::make('Chronic Conditions')
                                            ->schema([
                                                TextEntry::make('medicalProfile.chronic_conditions')
                                                    ->label('')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='p-4 bg-white rounded-lg shadow'>
                                                            <div class='flex items-center gap-2 mb-2'>
                                                                <svg class='w-5 h-5 text-yellow-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' />
                                                                </svg>
                                                                <h3 class='font-medium text-gray-900'>Chronic Conditions</h3>
                                                            </div>
                                                            <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
                                                        </div>
                                                    " : '<div class="p-4 text-gray-400">No chronic conditions recorded</div>')
                                                    ->html(),
                                            ]),

                                        Section::make('Current Medications')
                                            ->schema([
                                                TextEntry::make('medicalProfile.current_medications')
                                                    ->label('')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='p-4 bg-white rounded-lg shadow'>
                                                            <div class='flex items-center gap-2 mb-2'>
                                                                <svg class='w-5 h-5 text-green-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />
                                                                </svg>
                                                                <h3 class='font-medium text-gray-900'>Current Medications</h3>
                                                            </div>
                                                            <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
                                                        </div>
                                                    " : '<div class="p-4 text-gray-400">No medications recorded</div>')
                                                    ->html(),
                                            ]),

                                        Section::make('General Notes')
                                            ->schema([
                                                TextEntry::make('description')
                                                    ->label('')
                                                    ->formatStateUsing(fn($state) => $state ? "
                                                        <div class='p-4 bg-white rounded-lg shadow'>
                                                            <div class='flex items-center gap-2 mb-2'>
                                                                <svg class='w-5 h-5 text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' />
                                                                </svg>
                                                                <h3 class='font-medium text-gray-900'>General Notes</h3>
                                                            </div>
                                                            <p class='text-gray-700 whitespace-pre-line'>{$state}</p>
                                                        </div>
                                                    " : '<div class="p-4 text-gray-400">No general notes recorded</div>')
                                                    ->html(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->columns(3),

                // Documents Section
                Section::make('Documents')
                    ->hidden(fn($record) => $record->documents->isEmpty())
                    ->schema([
                        RepeatableEntry::make('documents')
                            ->label('')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('file_path')
                                            ->label('')
                                            ->formatStateUsing(fn($state) => "
                                                <div class='flex items-center gap-3 p-3 bg-white rounded-lg shadow hover:bg-gray-50'>
                                                    <svg class='w-8 h-8 text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z' />
                                                    </svg>
                                                    <div>
                                                        <p class='font-medium text-gray-900'>" . basename($state) . "</p>
                                                        <p class='text-sm text-gray-500'>" . Storage::size($state) . " KB</p>
                                                    </div>
                                                </div>
                                            ")
                                            ->html(),

                                        TextEntry::make('comments')
                                            ->label('')
                                            ->formatStateUsing(fn($state) => $state ? "
                                                <div class='p-3 bg-gray-50 rounded-lg'>
                                                    <p class='text-gray-700'>{$state}</p>
                                                </div>
                                            " : '')
                                            ->html(),
                                    ]),
                            ])
                            ->grid(1),
                    ]),
            ]);
    }

    protected static function getBloodTypeColor(string $bloodType): string
    {
        return match ($bloodType) {
            'A+' => 'red',
            'A-' => 'red',
            'B+' => 'yellow',
            'B-' => 'yellow',
            'AB+' => 'green',
            'AB-' => 'green',
            'O+' => 'blue',
            'O-' => 'blue',
            default => 'gray',
        };
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
