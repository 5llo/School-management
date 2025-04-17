<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ContestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $days = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
        ];

        $dayName = array_key_exists(Carbon::parse($this->date_start)->format('l'), $days) ? $days[Carbon::parse($this->date_start)->format('l')] : '';
        return [
            'name' => $this->name,
            'description' => $this->description,
            'date_start' => $this->date_start,
            'duration' => $this->duration,
            'day'=> $dayName,
            // 'teacher' => [
            //     'name' => $this->teacher->name,
            //     'email' => $this->teacher->email,
            // ],
            // 'students' => $this->students->map(function ($student) {
            //     return [
            //         'name' => $student->name,
            //     ];
            // }),
        ];
    }
    }

