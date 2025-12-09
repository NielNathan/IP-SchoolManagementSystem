<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Instructor;

class AdminScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['subject', 'instructor', 'students'])->get();

        $groupedSchedules= $schedules->groupBy('subject_id');
        
        $instructors = Instructor::all();

        return view('admin.edit', compact('schedules', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required',
            'instructor_id' => 'required',
            'grade_level' => 'required',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);

        $subject = Subject::firstOrCreate(['name' => $request->subject_name]);

        // Jika ada jadwal pelajaran yang bentrok
        $conflict = Schedule::where('day', $request->day)
            ->where('grade_level', $request->grade_level)
            ->where(function ($q) use ($request) {
                $q->whereBetween('time_start', [$request->time_start, $request->time_end])
                ->orWhereBetween('time_end', [$request->time_start, $request->time_end])
                ->orWhere(function ($q2) use ($request) {
                    $q2->where('time_start', '<=', $request->time_start)
                        ->where('time_end', '>=', $request->time_end);
                });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Jadwal bentrok! Sudah ada pelajaran lain pada jam tersebut.');
        }

        Schedule::create([
            'subject_id' => $subject->id,
            'instructor_id' => $request->instructor_id,
            'grade_level' => $request->grade_level, 
            'day' => $request->day,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'quota' => 30
        ]);

        return back()->with('success', 'Jadwal berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $conflict = Schedule::where('id', '!=', $id)
            ->where('day', $request->day)
            ->where('grade_level', $request->grade_level)
            ->where(function ($q) use ($request) {
                $q->whereBetween('time_start', [$request->time_start, $request->time_end])
                ->orWhereBetween('time_end', [$request->time_start, $request->time_end])
                ->orWhere(function ($q2) use ($request) {
                    $q2->where('time_start', '<=', $request->time_start)
                        ->where('time_end', '>=', $request->time_end);
                });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Jadwal bentrok! Tidak dapat memperbarui.');
        }

        $schedule->subject->update(['name' => $request->subject_name]);
        
        $schedule->update([
            'instructor_id' => $request->instructor_id,
            'grade_level' => $request->grade_level, 
            'day' => $request->day,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
        ]);

        return back()->with('success', 'Data diperbarui!');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $subject = $schedule->subject;
        
        $schedule->delete(); 
        $subject->delete(); 

        return back()->with('success', 'Data berhasil dihapus.');
    }
}