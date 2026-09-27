<?php

namespace App\Filament\Resources\InformationPages\Schemas;

use App\Enums\InformationCategory;
use App\Enums\PublishStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InformationPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Halaman Informasi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('category')
                            ->label('Kategori Informasi')
                            ->options(InformationCategory::class)
                            ->default(InformationCategory::Program)
                            ->required(),
                        Select::make('service_type_id')
                            ->label('Terkait Jenis Layanan (Bila Ada)')
                            ->relationship('serviceType', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Konten & Panduan Layanan')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi Ringkas Layanan / Info')
                            ->rows(3)
                            ->required(),
                        RichEditor::make('requirements')
                            ->label('Persyaratan & Kelengkapan Berkas')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'italic',
                                'link',
                            ]),
                        RichEditor::make('procedure')
                            ->label('Mekanisme & Alur Pengajuan')
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'italic',
                                'link',
                            ]),
                    ]),

                Section::make('Waktu, Lokasi & Kontak')
                    ->columns(3)
                    ->schema([
                        TextInput::make('service_hours')
                            ->label('Jam Layanan')
                            ->placeholder('Senin - Jumat, 08.00 - 15.00 WIB'),
                        TextInput::make('location')
                            ->label('Lokasi Pelayanan')
                            ->placeholder('Kantor Dinas Sosial / Loket Pelayanan Terpadu'),
                        TextInput::make('contact')
                            ->label('Kontak / Call Center')
                            ->placeholder('(0342) 801xxx / WhatsApp Layanan'),
                    ]),

                Section::make('Status Publikasi')
                    ->columns(3)
                    ->schema([
                        Select::make('publish_status')
                            ->label('Status Publikasi')
                            ->options(PublishStatus::class)
                            ->default(PublishStatus::Draft)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now()),
                        Select::make('manager_id')
                            ->label('Petugas Pengelola')
                            ->relationship('manager', 'name')
                            ->default(fn () => auth()->id())
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
