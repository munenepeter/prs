<?php

namespace App\Http\Livewire\Concerns;

use App\Models\User;
use Illuminate\Support\Str;
use App\Exports\DailyReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

trait WithExportDailyReport {

    public function exportExcel() {

        if ($this->has_filter) {
            $reports = $this->applyFilters();
        } else {
            $reports = $this->populateReports();
        }

        return Excel::download(
            new DailyReportsExport($reports->get()),
            $this->getExportFileName() . '.xlsx'
        );
    }

    public function exportPdf() {

        $header = [];

        if ($this->has_filter) {
            $reports = $this->applyFilters();
            $header = $this->getHeaderDetails();
        } else {
            $reports = $this->populateReports();
        }

        $reports = $reports->get();

        $pdfContent = view('daily-report-pdf', compact('reports', 'header'))->render();

        $pdf = PDF::loadHtml($pdfContent)
                    ->setWarnings(false)
                    ->setPaper('A4', 'portrait')
                    ->save($this->getExportFileName() . '.pdf');

        // Provide a download link to the saved pdf
        return response()->download(public_path($this->getExportFileName() . '.pdf'))->deleteFileAfterSend();
    }

    public function getExportFileName() {

        $filename = Str::headline(static::getName());

        if ($this->date_from) {
            $filename .= " from " . $this->date_from;
        }

        if ($this->date_to) {
            $filename .= " up to " . $this->date_to;
        }

        if ($this->project >= 1) {
            $filename .= " for the project " . data_get($this->projects, $this->project, '');
        }

        if ($this->task >= 1) {
            $filename .= " for the task " . data_get($this->tasks, $this->task, '');
        }

        return $filename;
    }

    public function getHeaderDetails():array {
        $header = [];

        if ($this->date_from) {
            $header['date'] = "From " . $this->date_from . " Up to " . $this->date_to;
        }

        if ($this->user) {
            $user = User::select(['firstname', 'lastname'])
                         ->where('id', $this->user)->first();

            $header['user']  = $user?->firstname . ' ' . $user?->lastname;
        }

        if ($this->project >= 1) {
            $header['project'] = data_get($this->projects, $this->project, '');
        }

        if ($this->task >= 1) {
            $header['task'] = data_get($this->tasks, $this->task, '');
        }
        if ($this->perfomance) {
            $header['perfomance'] = data_get($this->perfomances, $this->perfomance, '');
        }

        return $header;
    }
}
