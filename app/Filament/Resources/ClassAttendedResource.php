<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassAttendedResource\Pages;
use App\Filament\Resources\ClassAttendedResource\RelationManagers;
use App\Models\ClassAttended;
use App\Models\Kelas;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ClassAttendedResource extends Resource
{
    protected static ?string $model = ClassAttended::class;
    protected static ?string $navigationGroup = 'Kelas';
    protected static ?string $navigationLabel = 'Kelas yang diikuti';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'student';
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('student_id', Auth::user()->id_number)
            ->when(request()->get('class_id'), function($query, $class_id){
                return $query->where('class_id', $class_id);
            }, function($query){
                return $query;
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.date')
                    ->label('Hari')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class')
                    ->formatStateUsing(function ($state){
                        return Carbon::parse($state->time_start)->format('H:i'). '-' . Carbon::parse($state->time_end)->format('H:i');
                    })
                    ->label('Jam'),
                TextColumn::make('class.time_start')
                    ->label('Jam Mulai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.time_end')
                    ->label('Jam Selesai')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('class_id')
                ->label('Pilih Kelas')
                ->options(ClassAttended::all()->pluck('class.name', 'class_id'))
                ->query(function(Builder $query, $class_id){
                    if($class_id){
                        return $query->where('class_id', $class_id);
                    }
                    return $query;
                }),
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassAttendeds::route('/'),
            'create' => Pages\CreateClassAttended::route('/create'),
            'edit' => Pages\EditClassAttended::route('/{record}/edit'),
        ];
    }
}
