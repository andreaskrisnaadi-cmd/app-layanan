<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Enums\ReferralStatus;
use App\Models\NumberSequence;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrals';

    protected static ?string $title = 'Rujukan Lembaga Sosial / Mitra';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('referral_number')
                    ->label('Nomor Surat Rujukan')
                    ->default(fn () => NumberSequence::getNextNumber('RUJUK', now()->format('Ym'), 4))
                    ->required(),
                Select::make('referral_institution_id')
                    ->label('Lembaga Tujuan Rujukan')
                    ->relationship('referralInstitution', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('officer_id')
                    ->label('Petugas Pendamping')
                    ->relationship('officer', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                DatePicker::make('referral_date')
                    ->label('Tanggal Rujukan')
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->label('Status Rujukan')
                    ->options(ReferralStatus::class)
                    ->default(ReferralStatus::Draft)
                    ->required(),
                Textarea::make('service_result')
                    ->label('Hasil / Catatan Pelayanan dari Lembaga Rujukan')
                    ->rows(2)
                    ->columnSpanFull(),
                DateTimePicker::make('completed_at')
                    ->label('Waktu Selesai'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referral_number')
            ->columns([
                TextColumn::make('referral_number')
                    ->label('No. Rujukan')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('referralInstitution.name')
                    ->label('Lembaga Tujuan')
                    ->searchable(),
                TextColumn::make('referral_date')
                    ->label('Tgl Rujuk')
                    ->date('d M Y'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ReferralStatus $state): string => match ($state) {
                        ReferralStatus::Draft => 'gray',
                        ReferralStatus::Sent => 'info',
                        ReferralStatus::Accepted,
                        ReferralStatus::InService => 'warning',
                        ReferralStatus::Completed => 'success',
                        ReferralStatus::Declined,
                        ReferralStatus::Cancelled => 'danger',
                    }),
                TextColumn::make('officer.name')
                    ->label('Pendamping')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Buat Surat Rujukan'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('sendReferral')
                    ->label('Kirim')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === ReferralStatus::Draft)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => ReferralStatus::Sent]);
                        Notification::make()->title('Surat Rujukan Dikirim ke Lembaga')->info()->send();
                    }),
                Action::make('acceptReferral')
                    ->label('Diterima Lembaga')
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === ReferralStatus::Sent)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => ReferralStatus::Accepted]);
                        Notification::make()->title('Rujukan Diterima oleh Lembaga')->warning()->send();
                    }),
                Action::make('startService')
                    ->label('Mulai Pelayanan')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === ReferralStatus::Accepted)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => ReferralStatus::InService]);
                        Notification::make()->title('Pelayanan Rujukan Dimulai')->primary()->send();
                    }),
                Action::make('completeReferral')
                    ->label('Selesai Rujukan')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === ReferralStatus::InService)
                    ->form([
                        Textarea::make('service_result')
                            ->label('Hasil / Laporan Akhir Pelayanan dari Lembaga Rujukan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => ReferralStatus::Completed,
                            'service_result' => $data['service_result'],
                            'completed_at' => now(),
                        ]);
                        Notification::make()->title('Pelayanan Rujukan Selesai')->success()->send();
                    }),
                Action::make('declineReferral')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, [ReferralStatus::Sent, ReferralStatus::Accepted]))
                    ->form([
                        Textarea::make('reason')
                            ->label('Alasan Penolakan dari Lembaga')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => ReferralStatus::Declined,
                            'service_result' => 'Ditolak lembaga: ' . $data['reason'],
                        ]);
                        Notification::make()->title('Rujukan Ditolak oleh Lembaga')->danger()->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
