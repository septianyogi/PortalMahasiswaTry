<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddClassResource\Pages;
use App\Filament\Resources\AddClassResource\RelationManagers;
use App\Models\AddClass;
use App\Models\ClassAttended;
use App\Models\Kelas;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AddClassResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $navigationGroup = 'Kelas';
    protected static ?string $navigationLabel= 'Tambah Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'student';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Tambah Kelas')
            ->description('Silahkan masukkan kelas yang ingin anda ikuti')
            ->deferLoading()
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->label('Kode Kelas'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Kelas'),
                TextColumn::make('dosen.name')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester')
                    ->sortable()
                    ->label('Semester'),
                TextColumn::make('date')
                    ->label('Hari'),
                TextColumn::make('time_start')
                    ->formatStateUsing(function ($state, Kelas $kelas){
                        return Carbon::parse($kelas->time_start)->format('H:i'). '-' . Carbon::parse($kelas->time_end)->format('H:i');
                    })
                    ->label('Jam'),
                TextColumn::make('quota')
                    ->label('Kuota'),
                TextColumn::make('room')
                    ->label('Ruang'),
            ])
            ->filters([
                SelectFilter::make('semester')
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
                    ->label('Semester'),
            ])
            ->actions([
                Tables\Actions\Action::make('tambah')
                    ->label('Tambah')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->action(fn (Kelas $record)=> self::AddClass($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function AddClass(Kelas $kelas)
    {
        $exist = ClassAttended::where('student_id', Auth::user()->id_number)
            ->where('class_id', $kelas->id)
            ->first();
        
        if ($exist) {
            Notification::make()
                ->title('Anda sudah terdaftar dalam kelas')
                ->danger()
                ->send();
        } else {
            $recentQuota = Kelas::where('id', $kelas->id)->first()->quota;

            if ($recentQuota <= 0) {
                Notification::make()
                    ->title('Kelas sudah penuh')
                    ->danger()
                    ->send();
            }else{
                $kelas->update([
                    'quota' => $recentQuota - 1,
                ]);
        
                ClassAttended::create([
                    'student_id' => Auth::user()->id_number,
                    'student_name' => Auth::user()->name,
                    'class_id' => $kelas->id,
                ]);
        
                Notification::make()
                    ->title('Berhasil mendaftar kelas')
                    ->success()
                    ->send();
            }
        }

       
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
            'index' => Pages\ListAddClasses::route('/'),
            'create' => Pages\CreateAddClass::route('/create'),
            'edit' => Pages\EditAddClass::route('/{record}/edit'),
        ];
    }
}
