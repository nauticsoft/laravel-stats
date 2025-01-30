<?php

namespace Nauticsoft\LaravelStats;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Nauticsoft\LaravelStats\DataTransferObjects\Metric;
use stdClass;
use UnexpectedValueException;

class Stats
{
    private string $dateFormat = 'Y-m-d';

    private ?string $type = null;

    private ?Carbon $startDate = null;

    private ?Carbon $endDate = null;

    public function dateFormat(string $format): self
    {
        $this->dateFormat = $format;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function from(Carbon $date): self
    {
        $this->startDate = $date;

        return $this;
    }

    public function until(Carbon $date): self
    {
        $this->endDate = $date;

        return $this;
    }

    /**
     * @return Collection<int, array{'date': string, 'key': string, 'value': int}>
     */
    public function get(): Collection
    {
        if ($this->type === null) {
            throw new UnexpectedValueException('No type defined.');
        }

        $query = DB::table('stats')->where('type', $this->type);

        if ($this->startDate !== null) {
            $query->where('timestamp', '>=', $this->startDate->timestamp);
        }

        if ($this->endDate !== null) {
            $query->where('timestamp', '<=', $this->endDate->timestamp);
        }

        $query->orderBy('timestamp', 'desc');

        // @phpstan-ignore return.type
        return $query->get()->map(fn (stdClass $item) => [
            // @phpstan-ignore argument.type
            'date' => Carbon::createFromTimestamp($item->timestamp)->format($this->dateFormat),
            'key' => $item->key,
            'value' => $item->value,
        ]);
    }

    public function save(Carbon $date, string $key, int $value): self
    {
        if ($this->type === null) {
            throw new UnexpectedValueException('No type defined.');
        }

        DB::table('stats')->upsert([
            ['timestamp' => $date->timestamp, 'type' => $this->type, 'key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()],
        ],
            ['timestamp', 'type', 'key'],
            ['value', 'updated_at']
        );

        return $this;
    }

    /**
     * @param  Metric[]  $metrics
     */
    public function bulkSave(array $metrics): self
    {
        if ($this->type === null) {
            throw new UnexpectedValueException('No type defined.');
        }

        $rows = collect($metrics)->map(function (Metric $metric) {
            return [
                'timestamp' => $metric->date->timestamp,
                'type' => $this->type,
                'key' => $metric->key,
                'value' => $metric->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('stats')->upsert($rows, ['timestamp', 'type', 'key'], ['value', 'updated_at']);

        return $this;
    }
}
