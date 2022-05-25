<?php

namespace App\Services\Exam;

use App\Models\ExamRecord;
use Illuminate\Support\Facades\DB;
use App\Services\Exam\ExamSlotService;
use App\Services\Subject\SubjectService;

class ExamRecordService{

    protected ExamSlotService $examSlot;
    protected SubjectService $subject;

    public function __construct(ExamSlotService $examSlot, SubjectService $subject)
    {
        $this->examSlot = $examSlot;
        $this->subject = $subject;
    }

    public function getAllExamRecordsInSectionAndSubject($section, $subject)
    {
        return ExamRecord::where(['section_id' => $section, 'subject_id' => $subject])->get();
    }

    public function getAllExamRecordsInSection($section)
    {
        return ExamRecord::where('section_id', $section)->get();
    }

    public function getAllUserExamRecordInSubject($user, $subject)
    {
        return ExamRecord::where(['user_id' => $user, 'subject_id' => $subject]);
    }

    public function createExamRecord($records)
    {
        if (auth()->user()->hasRole('teacher') && $this->subject->getSubjectById( $records['subject_id'])->teachers->where('id', auth()->user()->id)->isEmpty()) {
            return session()->flash('danger', 'You are not authorized to create exam record for this subject');
        }
        //started transaction to make sure everythng ran smoothly before saving
        DB::beginTransaction();

        foreach ($records['exam_records'] as $record) {
            // makes sure student marks and exam slot id are not null just an extra check ad=s this is already doine in request class
            if ($record['student_marks'] == null || $this->examSlot->getExamSlotById($record['exam_slot_id'])->total_marks == null) {
                //stop db transaction and return error
                DB::rollback();
                return session()->flash('danger', 'Incomplete records submitted');
            }

            // checks if student marks is less than total marks
            if ($record['student_marks'] > $this->examSlot->getExamSlotById($record['exam_slot_id'])->total_marks) {
                //stop db transaction and return error
                DB::rollback();
                return session()->flash('danger', 'Student marks cannot be greater than total marks');
            }

            // creates exam record or updates if records already exists
            
            ExamRecord::updateOrCreate(
                ['user_id' => $records['user_id'],
                'section_id' => $records['section_id'],
                'subject_id' => $records['subject_id'],
                'exam_slot_id' => $record['exam_slot_id'],
            ],
            [
                'student_marks' => $record['student_marks'],
            ]);
        } 
        
        DB::commit();

        return session()->flash('success', 'Exam Records Created Successfully');
    }
}