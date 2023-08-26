<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyReportsExport implements FromCollection, WithMapping, WithHeadings
{
    public function __construct(protected Collection $collection)
    {

    }

    public function headings(): array
    {
        return [
            'User',
            'Project Name',
            'Task Name',
            'Unit Type',
            'Completed Tasks',
            'Duration',
            'Units completed per hour',
            'Start',
            'End',
            'Date',
        ];
    }


    public function map($report): array
    {
        return [
            ucfirst($report?->user?->fullname),
            ucfirst($report->project->name),
            ucfirst($report->task->name),
            $report->task->unit_type->name,
            $report->units_completed,
            $report->duration,
            ($report->hourlyRate === 0) ? "0" : $report->hourlyRate,
            $report->started_at->format('H:i:s'),
            $report->ended_at->format('H:i:s'),
            $report->reported_at->format('d/m/Y')
//            Date::dateTimeToExcel($invoice->created_at),
        ];
    }

    public function collection(): Collection
    {
        return $this->collection;
    }
}
