<?php
namespace App\Helper;

use App\Models\CustomerNotes;
use App\Models\NoteOfTask;
use App\Models\Notification;

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
  public static function CreateNotification($title, $content, $user_id, $relation, $relation_id){
    $notification = new Notification();
    $notification->title = $title;
    $notification->content = $content;
    $notification->user_id = $user_id;
    $notification->relation = $relation;
    $notification->relation_id = $relation_id;
    $notification->save();
    //return $notification;
  }

  public static function randImage(){
    $image = array(
      ['https://taimienphi.vn/tmp/cf/aut/mAKI-top-anh-dai-dien-dep-chat-1.jpg'],
      ['https://imgt.taimienphi.vn/cf/Images/np/2020/1/3/top-anh-dai-dien-dep-chat-5.jpg'],
      ['https://imgt.taimienphi.vn/cf/Images/np/2020/1/3/top-anh-dai-dien-dep-chat-6.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-anime-dep_104204759.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-cap-doi-dep_104204984.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-che-hai_104205084.png'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-chibi_104205184.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-cho-facebook_104205205.jpg'],
    );
    return implode("", $image[array_rand($image)]);
  }
}