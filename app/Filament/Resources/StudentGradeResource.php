<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentGradeResource\Pages;
use App\Filament\Resources\StudentGradeResource\RelationManagers;
use App\Models\ClassAttended;
use App\Models\StudentGrade;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StudentGradeResource extends Resource
{
    protected static ?string $model = ClassAttended::class;
    protected static ?string $navigationLabel = 'Nilai';
    protected static ?string $navigationGroup = 'Kelas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'student';
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('student_id', Auth::user()->id_number);
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
            
            ->deferLoading()
            ->columns([
                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendance')
                    ->label('Kehadiran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('absent')
                    ->label('Tidak Hadir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignment_1')
                    ->label('Tugas 1'),
                TextColumn::make('assignment_2')
                    ->label('Tugas 2'),
                TextColumn::make('assignment_3')
                    ->label('Tugas 3'),
                TextColumn::make('assignment_4')
                    ->label('Tugas 4'),
                TextColumn::make('mid_exam')
                    ->label('UTS'),
                TextColumn::make('final_exam')
                    ->label('UAS'),
                TextColumn::make('final_score')
                    ->label('Nilai Akhir'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListStudentGrades::route('/'),
        ];
    }
}
