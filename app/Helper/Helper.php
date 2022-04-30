<?php
namespace App\Helper;

use App\Models\CustomerNotes;
use App\Models\NoteOfTask;

class Helper
{
  public static function CreateNoteOfTask($task_id, $user_id, $content){
    $note = new NoteOfTask();
    $note->task_id = $task_id;
    $note->user_id = $user_id;
    $note->content = $content;
    $note->save();
    return $note;
  }
  public static function CreateNoteOfCustomer($customer_id, $user_id, $content){
    $note = new CustomerNotes();
    $note->customer_id = $customer_id;
    $note->user_id = $user_id;
    $note->content = $content;
    $note->save();
    return $note;
  }
}