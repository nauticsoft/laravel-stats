<?php

namespace Nauticsoft\LaravelStats;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Nauticsoft\LaravelStats\Models\Stat;
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

        $query = Stat::query()->where('type', $this->type);

        if ($this->startDate !== null) {
            $query->where('timestamp', '>=', $this->startDate->timestamp);
        }

        if ($this->endDate !== null) {
            $query->where('timestamp', '<=', $this->endDate->timestamp);
        }

        $query->orderBy('timestamp', 'desc');

        return $query->get()->map(fn ($item) => [
            'date' => $item->timestamp->format($this->dateFormat),
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
            ['timestamp' => $date->timestamp, 'type' => $this->type, 'key' => $key, 'value' => $value],
        ],
            ['timestamp', 'type', 'key'],
            ['value']
        );

        return $this;
    }
}
