<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Phpsa\FilamentPasswordReveal\Password as FilamentPasswordRevealPassword;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    
    protected static ?string $navigationGroup = 'User';

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('npm')
                    ->label('NPM')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Section::make()
                    ->columns(3)
                    ->schema([
                        Select::make('fakultas_id')
                            ->label('Fakultas')
                            ->options(Fakultas::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('jurusan_id')
                            ->label('Jurusan')
                            ->options(Jurusan::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                                5 => '5',
                                6 => '6',
                                7 => '7',
                                8 => '8',
                            ])
                            ->required(),
                    ]),
                TextInput::make('email')
                    ->label('Email')
                    ->required(),
                FilamentPasswordRevealPassword::make('password')
                    ->label('Password')
                    ->revealable(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('npm')
                    ->label('NPM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fakultas.name')
                    ->label('Fakultas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jurusan.name')
                    ->label('Jurusan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->searchable()
                    ->sortable(),
                
            ])
            ->filters([
                SelectFilter::make('fakultas_id')
                    ->label('Fakultas')
                    ->options(Fakultas::all()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                        6 => '6',
                        7 => '7',
                        8 => '8',
                    ])
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
