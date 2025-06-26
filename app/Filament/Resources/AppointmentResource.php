<?php

namespace App\Filament\Resources;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'name') // Assuming Lead model has a 'name' field
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('provider_id')
                    ->label('Provider')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('appointment_type_id')
                    ->label('Appointment Type')
                    ->options(AppointmentType::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->required()
                    ->native(false)
                    ->minutesStep(15),

                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->suffix('minutes')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'no_show' => 'No Show',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('provider.name')
                    ->label('Provider')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type.name')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->suffix(' mins')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        'no_show' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'no_show' => 'No Show',
                    ]),

                Tables\Filters\SelectFilter::make('provider_id')
                    ->label('Provider')
                    ->relationship('provider', 'name'),

                Tables\Filters\SelectFilter::make('appointment_type_id')
                    ->label('Type')
                    ->relationship('type', 'name'),

                Tables\Filters\Filter::make('scheduled_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('scheduled_at', '>=', $date),
                            )
                            ->when($data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('scheduled_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You could add relation managers here if needed
            // For example, for the visit relationship
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => AppointmentResource\Pages\ListAppointments::route('/'),
            'create' => AppointmentResource\Pages\CreateAppointment::route('/create'),
            'edit' => AppointmentResource\Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
