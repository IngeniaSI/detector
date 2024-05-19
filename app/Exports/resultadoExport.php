<?php

namespace App\Exports;

use App\Models\encuesta;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class resultadoExport implements FromCollection, WithHeadings
{
    protected $responses;
    protected $questions;
    protected $nameQuestions;

    public function __construct($responses, $nameQuestions, array $questions)
    {
        $this->responses = $responses;
        $this->questions = $questions;
        $this->nameQuestions = $nameQuestions;
    }
    public function collection()
    {
        return $this->responses->map(function($response) {
            $row = [];
            foreach ($this->nameQuestions as $question) {
                $row[] = $response[$question] ?? null;
            }
            return $row;
        });
    }

    public function headings(): array
    {
        return $this->questions;
    }
}
