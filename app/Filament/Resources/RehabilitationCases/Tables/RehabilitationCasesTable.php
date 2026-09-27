<?php

namespace App\Filament\Resources\RehabilitationCases\Tables;

use App\Enums\RehabilitationCaseStatus;
use App\Enums\RehabilitationHandlingType;
use App\Models\ClientCategory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RehabilitationCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('case_number')
                    ->label('No. Kasus')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.category.name')
                    ->label('Kategori PPKS')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('handling_type')
                    ->label('Penanganan')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('status')
                    ->label('Status Kasus')
                    ->badge()
                    ->color(fn (RehabilitationCaseStatus $state): string => match ($state) {
                        RehabilitationCaseStatus::Received => 'gray',
                        RehabilitationCaseStatus::Assessment => 'info',
                        RehabilitationCaseStatus::ServicePlanning => 'warning',
                        RehabilitationCaseStatus::InService => 'primary',
                        RehabilitationCaseStatus::Closed => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('officer.name')
                    ->label('Petugas PJ')
                    ->toggleable(),
                TextColumn::make('received_at')
                    ->label('Diterima')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('client_category_id')
                    ->label('Kategori PPKS')
                    ->options(fn () => ClientCategory::pluck('name', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        filled($data['value'])
                            ? $query->whereHas('client', fn (Builder $q) => $q->where('client_category_id', $data['value']))
                            : $query
                    ),
                SelectFilter::make('status')
                    ->label('Status Kasus')
                    ->options(RehabilitationCaseStatus::class),
                SelectFilter::make('handling_type')
                    ->label('Jenis Penanganan')
                    ->options(RehabilitationHandlingType::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('startAssessment')
                    ->label('Mulai Assessment')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === RehabilitationCaseStatus::Received)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => RehabilitationCaseStatus::Assessment]);
                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => RehabilitationCaseStatus::Assessment->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Kasus masuk tahap assessment kebutuhan klien.',
                        ]);
                        Notification::make()->title('Tahap Assessment Dimulai')->success()->send();
                    }),
                Action::make('planService')
                    ->label('Rencana Layanan')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === RehabilitationCaseStatus::Assessment)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => RehabilitationCaseStatus::ServicePlanning]);
                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => RehabilitationCaseStatus::ServicePlanning->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Penyusunan rencana layanan / rujukan.',
                        ]);
                        Notification::make()->title('Rencana Layanan Disusun')->success()->send();
                    }),
                Action::make('startService')
                    ->label('Mulai Pelayanan')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === RehabilitationCaseStatus::ServicePlanning)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => RehabilitationCaseStatus::InService]);
                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => RehabilitationCaseStatus::InService->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pelayanan / rujukan aktif berjalan.',
                        ]);
                        Notification::make()->title('Pelayanan Berjalan')->success()->send();
                    }),
                Action::make('startMonitoring')
                    ->label('Mulai Monitoring')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === RehabilitationCaseStatus::InService)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => RehabilitationCaseStatus::Monitoring]);
                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => RehabilitationCaseStatus::Monitoring->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Kasus masuk tahap monitoring perkembangan berkala.',
                        ]);
                        Notification::make()->title('Tahap Monitoring Dimulai')->warning()->send();
                    }),
                Action::make('closeCase')
                    ->label('Tutup Kasus')
                    ->icon('heroicon-o-lock-closed')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, [
                        RehabilitationCaseStatus::InService,
                        RehabilitationCaseStatus::Monitoring,
                    ]))
                    ->form([
                        Textarea::make('handling_result')
                            ->label('Hasil Akhir Penanganan & Evaluasi')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => RehabilitationCaseStatus::Closed,
                            'handling_result' => $data['handling_result'],
                            'closed_at' => now(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => RehabilitationCaseStatus::Closed->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Kasus ditutup dengan hasil: ' . $data['handling_result'],
                        ]);

                        Notification::make()->title('Kasus Berhasil Ditutup')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
