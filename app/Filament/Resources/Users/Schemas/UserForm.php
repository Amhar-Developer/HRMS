<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Position;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Informations')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(20)
                            ->regex('/^\+?[0-9\-\s\(\)]+$/')
                            ->default(null),

                        DatePicker::make('date_of_birth'),

                        TextInput::make('address')
                            ->default(null)
                            ->columnSpanFull(),

                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),

                Section::make('Employment Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('employee_id')
                            ->label('Employee Code')
                            ->readOnly()
                            ->unique(ignoreRecord: true)
                            ->hiddenOn('create'),

                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('position_id')
                            ->relationship('position', 'title')
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->options(function (Get $get) {
                                $departmentId = $get('department_id');
                                if (! $departmentId) {
                                    return [];
                                }

                                return Position::where('department_id', $departmentId)
                                    ->pluck('title', 'id')
                                    ->toArray();
                            }),

                        DatePicker::make('hire_date')
                            ->required(),

                        ToggleButtons::make('employment_type')
                            ->label('Employment Type')
                            ->options([
                                'full_time' => 'Full Time',
                                'part_time' => 'Part Time',
                                'contract' => 'Contract',
                                'intern' => 'Intern',
                            ])
                            ->colors([
                                'full_time' => 'info',
                                'part_time' => 'warning',
                                'contract' => 'success',
                                'intern' => 'danger',
                            ])
                            ->columnSpanFull()
                            ->grouped()
                            // ->native(false)
                            ->required()
                            ->default('full_time'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on_leave' => 'On Leave',
                                'terminated' => 'Terminated',
                            ])
                            ->native(false)
                            ->required()
                            ->default('active'),

                        TextInput::make('salary')
                            ->numeric()
                            ->required()
                            // ->rule(function (Get $get) {
                            //     return function (string $attribute, $value, Closure $fail) use ($get) {
                            //         $positionId = $get('position_id');

                            //         if (! $positionId || $value === null) {
                            //             return;
                            //         }

                            //         $position = Position::find($positionId);
                            //         if (! $position) {
                            //             return;
                            //         }

                            //         if (! is_null($position->min_salary) && $value < $position->min_salary) {
                            //             $fail("Salary must be at least {$position->min_salary} for this position.");
                            //         }

                            //         if (! is_null($position->max_salary) && $value > $position->max_salary) {
                            //             $fail("Salary cannot exceed {$position->max_salary} for this position.");
                            //         }
                            //     };
                            // })
                            ->helperText(function (Get $get) {
                                $positionId = $get('position_id');
                                if (! $positionId) {
                                    return null;
                                }

                                $position = Position::find($positionId);
                                if (! $position) {
                                    return null;
                                }

                                if ($position->min_salary && $position->max_salary) {
                                    return "Allowed range: {$position->min_salary} – {$position->max_salary}";
                                }

                                if ($position->min_salary) {
                                    return "Minimum salary: {$position->min_salary}";
                                }

                                if ($position->max_salary) {
                                    return "Maximum salary: {$position->max_salary}";
                                }

                                return null;
                            }),
                    ]),
                Section::make('Emergency Contact')
                    ->schema([
                        TextInput::make('emergency_contact_name')
                            ->default(null),
                        TextInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->tel()
                            ->maxLength(20)
                            ->regex('/^\+?[0-9\-\s\(\)]+$/')
                            ->default(null),
                    ]),
            ]);
    }
}
