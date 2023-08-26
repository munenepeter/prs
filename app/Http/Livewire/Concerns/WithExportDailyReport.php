<?php

namespace App\Http\Livewire\Concerns;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;
use App\Exports\DailyReportsExport;
use Maatwebsite\Excel\Facades\Excel;

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
        if ($this->has_filter) {
            $reports = $this->applyFilters();
        } else {
            $reports = $this->populateReports();
        }

        // dd($reports->get()->toArray());

        $reports = $reports->get();

        $pdfContent = view('daily_report_pdf', compact('reports'))->render();

        // Generate PDF
        $pdf = PDF::loadHtml($pdfContent)->setWarnings(false);

        // Set paper size and orientation if needed
        $pdf->setPaper('A4', 'portrait');

        // Save the PDF
        $pdf->save($this->getExportFileName() . '.pdf');

        // Provide a download link to the saved PDF
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
}
