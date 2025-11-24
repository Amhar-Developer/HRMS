<?php

namespace App\Filament\Hr\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in'),
                TimePicker::make('check_out'),
                // TextInput::make('status')
                //     ->required()
                //     ->default('present'),
                ToggleButtons::make('status')
                    ->label('Status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'half-day' => 'Half Day',
                        'on_leave' => 'On Leave',
                    ])
                    // ->native(false)
                    ->required()
                    ->grouped()
                    ->columnSpanFull()
                    ->default('present'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
