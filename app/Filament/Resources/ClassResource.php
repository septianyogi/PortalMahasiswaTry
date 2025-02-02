<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassResource\Pages;
use App\Filament\Resources\ClassResource\RelationManagers;
use App\Models\Class;
use App\Models\Dosen;
use App\Models\Jurusan;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ClassResource extends Resource
{
    protected static ?string $model = Kelas::class;

    
    protected static ?string $navigationGroup = 'Kelas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('jurusan_id')
                    ->label('Jurusan')
                    ->options(Jurusan::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Kelas')
                    ->required(),
                Select::make('dosen_id')
                    ->label('Dosen')
                    ->options(Dosen::all()->pluck('name', 'nip'))
                    ->searchable()
                    ->required(),
                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                    ])
                    ->required(),
                
                Fieldset::make('Waktu')
                    ->schema([
                        Section::make()
                            ->columns(3)
                            ->schema([
                                Select::make('date')
                                    ->label('Hari')
                                    ->options([
                                        'Senin' => 'Senin',
                                        'Selasa' => 'Selasa',
                                        'Rabu' => 'Rabu',
                                        'Kamis' => 'Kamis',
                                        'Jumat' => 'Jumat',
                                        'Sabtu' => 'Sabtu',
                                        'Minggu' => 'Minggu',
                                    ])
                                ->required(),
                                TimePicker::make('time_start')
                                    ->label('Jam Mulai')
                                    ->seconds(false)
                                    ->required(),
                                TimePicker::make('time_end')
                                    ->label('Jam Selesai')
                                    ->seconds(false)
                                    ->required(),
                    ]),
                ]),
                TextInput::make('quota')
                    ->label('Kuota')
                    ->required(),
                TextInput::make('room')
                    ->label('Ruang'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->label('Kode Kelas'),
                TextColumn::make('name')
                    ->sortable()
                    ->label('Nama Kelas'),
                TextColumn::make('dosen.name')
                    ->label('Dosen'),
                TextColumn::make('semester')
                    ->sortable()
                    ->label('Semester'),
                TextColumn::make('date')
                    ->label('Hari'),
                TextColumn::make('time_start')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('H:i'))
                    ->label('Jam Mulai'),
                TextColumn::make('time_end')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('H:i'))
                    ->label('Jam Selesai'),
                TextColumn::make('quota')
                    ->label('Kuota'),
                TextColumn::make('room')
                    ->label('Ruang'),
                
            ])
            ->filters([
                SelectFilter::make('dosen_id')
                    ->label('Filter Dosen')
                    ->options(fn () => Dosen::all()->pluck('name', 'nip')),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClass::route('/create'),
            'edit' => Pages\EditClass::route('/{record}/edit'),
        ];
    }
}
