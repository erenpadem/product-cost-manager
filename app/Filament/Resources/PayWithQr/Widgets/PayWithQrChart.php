<?php

namespace App\Filament\Resources\PayWithQr\Widgets;

use App\Models\PayWithQr;
use Filament\Widgets\ChartWidget;

class PayWithQrChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Son 30 gün — günlük net tahsilat';

    protected ?string $description = 'Her gün için indirim sonrası tutarların toplamı.';

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();
        $labels = [];
        $values = [];

        $records = PayWithQr::query()
            ->where('created_at', '>=', $start)
            ->get(['toplam_tutar', 'indirim_oranı', 'created_at']);

        $byDay = $records->groupBy(fn (PayWithQr $row) => $row->created_at->toDateString());

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $key = $date->toDateString();
            $labels[] = $date->format('d.m');
            $dayRows = $byDay->get($key, collect());
            $values[] = round(
                (float) $dayRows->sum(fn (PayWithQr $r) => (float) $r->net_tutar),
                2
            );
        }

        return [
            'datasets' => [
                [
                    'label' => 'Net tahsilat (₺)',
                    'data' => $values,
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.1)',
                    'fill' => true,
                    'tension' => 0.25,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
