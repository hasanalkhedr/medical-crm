<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalProfileRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalProfile';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('blood_type')
                    ->options([
                        'A+' => 'A+',
                        'A-' => 'A-',
                        'B+' => 'B+',
                        'B-' => 'B-',
                        'AB+' => 'AB+',
                        'AB-' => 'AB-',
                        'O+' => 'O+',
                        'O-' => 'O-',
                    ]),

                Forms\Components\TextInput::make('height')
                    ->numeric()
                    ->suffix('cm'),

                Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->suffix('kg'),

                Forms\Components\Textarea::make('known_allergies')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('chronic_conditions')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('current_medications')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blood_type'),
                Tables\Columns\TextColumn::make('height')
                    ->suffix(' cm'),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' kg'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
