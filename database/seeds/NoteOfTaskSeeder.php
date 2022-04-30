<?php

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteOfTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = Task::get();
        foreach($tasks as $task){
            DB::table('note_of_tasks')->insert(
                [
                    'task_id' => $task->id,
                    'user_id' => $task->user_id,
                    'content' => 'Tạo mới công việc',
                    'created_at' => new DateTime,
                    'updated_at' => new DateTime,
                ]
                );
        }
    }
}
