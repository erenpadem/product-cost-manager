<?php

namespace App\Filament\Resources\PayWithQr\Widgets;

use App\Models\PayWithQr;
use Filament\Schemas\Components\Component;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PayWithQrStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getSectionContentComponent(): Component
    {
        return parent::getSectionContentComponent()
            ->extraAttributes([
                'class' => 'pay-with-qr-stats-section',
            ], merge: true);
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $count = PayWithQr::query()->count();
        $brut = (float) PayWithQr::query()->sum('toplam_tutar');

        $net = (float) (PayWithQr::query()->selectRaw(
            'COALESCE(SUM(toplam_tutar * (1 - (indirim_oranı / 100))), 0) as net'
        )->value('net') ?? 0);

        $avgDiscount = (float) (PayWithQr::query()->avg('indirim_oranı') ?? 0);

        return [
            Stat::make('QR satış adedi', number_format($count, 0, ',', '.'))
                ->description('Kayıtlı QR ödeme sayısı')
                ->icon(Heroicon::OutlinedQrCode),
            Stat::make('Brüt ciro', '₺ '.number_format($brut, 2, ',', '.'))
                ->description('Toplam tutarların toplamı')
                ->icon(Heroicon::OutlinedBanknotes),
            Stat::make('Net tahsilat (tahmini)', '₺ '.number_format($net, 2, ',', '.'))
                ->description('İndirim oranı uygulanmış toplam')
                ->icon(Heroicon::OutlinedCurrencyDollar),
            Stat::make('Ortalama indirim oranı', number_format($avgDiscount, 2, ',', '.').' %')
                ->description('Tüm kayıtların ortalaması')
                ->icon(Heroicon::OutlinedReceiptPercent),
        ];
    }
}
