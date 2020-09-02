<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Container\Contracts\Users\UsersContract;
use App\Container\Contracts\Lectures\LecturesContract;
use Illuminate\Support\Facades\Auth;
use App\Container\Contracts\Calenders\CalendersContract;
use Illuminate\Support\Facades\Mail;
use App\Container\Contracts\Payments\PaymentsContract;
use App\Container\Contracts\Users\UserEnrollsContract;
use App\Container\Contracts\PromoCodes\PromoCodesContract;
use App\Notifications\BookSession;
use App\Notifications\BookSessionStudent;
use App\Notifications\BookPendingSession;
use App\Notifications\BookPendingSessionStudent;
use App\Container\Contracts\Payments\FawryContract;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $user_enrolls;
    protected $promocode;
    protected $user;
    protected $payments;
    protected $lecture;
    protected $order;
    protected $fawry;
    protected $schedule;

  public function __construct(
    UserEnrollsContract $user_enrolls,
    PromoCodesContract $promocode,
    UsersContract $user,
    PaymentsContract $payments,
    Lecture $lecture,
    Order $order,
    FawryContract $fawry,
    Schedule $schedule
  ) {
    //parent::__construct();
    $this->user_enrolls = $user_enrolls;
    $this->promocode    = $promocode;
    $this->user         = $user;
    $this->lecture      = $lecture;
    $this->payments     = $payments;
    $this->order        = $order;
    $this->fawry        = $fawry;
    $this->schedule     = $schedule;
  }

  public function index()
  {
    $lecture_ids = array_values($this->user_enrolls->getUnPaid(Auth::id())->where('type', 'group')->pluck('lecture_id')->toArray());
    $groups = $this->user_enrolls->getUnPaid(Auth::id())->where('type', 'group')->toArray();
    if($groups){
      $groupsIds = array_unique(array_column($groups, 'lecture_id'));
      foreach($groupsIds as $key => $groupsId){
        $allGroups[$key] = $this->user_enrolls->getUnPaid(Auth::id())->where('type', 'group')->where('lecture_id', $groupsId)->first()->toArray();
      }
    }
    $items = $this->user_enrolls->getUnPaid(Auth::id());
 

    $pending_lecture_ids = array_values($this->user_enrolls->getPendingItems(Auth::id())->where('type', 'group')->pluck('lecture_id')->toArray());
    $pending_groups = $this->user_enrolls->getPendingItems(Auth::id())->where('type', 'group')->toArray();
    if($pending_groups){
      $pending_groupsIds = array_unique(array_column($pending_groups, 'lecture_id'));
      foreach($pending_groupsIds as $key => $pending_groupsId){
        $allPendingGroups[$key] = $this->user_enrolls->getPendingItems(Auth::id())->where('type', 'group')->where('lecture_id', $pending_groupsId)->first()->toArray();
      }
    }
    $pending_items = $this->user_enrolls->getPendingItems(Auth::id());
 
    return parent::view('index', [
      "items" => $items,
      "lecture_ids" => $lecture_ids, 
      "groups"  => isset($allGroups) ? $allGroups : null,
      "pending_items" => $pending_items,
      "pending_lecture_ids" => $pending_lecture_ids, 
      "pending_groups"  => isset($allPendingGroups) ? $allPendingGroups : null
    ]);
  }

  public function checkout(Request $request)
  {
    $count = ($request->items != null) ? count($request->items) : 0;
    if ($count == 0 || $request->items == null) {
        return response()->json(['error' => 'no items found for checkout'],404);   

    }

    if ($request->promo_code) {
      $request->request->add([
        'percent' => $this->promocode->use($request->promo_code)
      ]);
    }

    $url = $this->user_enrolls->checkout(Auth::id(), $request);

    if (is_string($url)) {
      return response()->json(['url' => $url],200);   
    } elseif ($url instanceof Order) {
      
      return  $this->freeLectures($url);   

    }

    return response()->json(['error' => 'no items found for checkout'],400);   

  }

  public function freeLectures(Order $order)
  {
    $this->user_enrolls->booked($order);
    //alert()->success(__('frontend.success_checkout'), __('frontend.thanks'))->persistent('Close');
    //return response()->json(['sucess' => 'thanks'],200);   


    $lectures = json_decode($order->lectures, 'Array');

    $lectures_array = [];
    foreach ($lectures as $lecture) {
      $lectures_array['lecture_id'] = $lecture['lecture_id'];
    }

    $lectures_ids = array_values($lectures_array);

    $lecture_id = $this->lecture->whereIn('id', $lectures_ids)->pluck('id')->toArray();
    $lecture = $this->lecture->where('id', $lecture_id)->first();
    $teacher = $this->user->get($lecture->teacher_id);
    $user = $this->user->get(Auth::id());

    $schedules = $this->schedule->whereIn('lecture_id', $lectures_ids)->get();

    $schedules_array = [];
    foreach($schedules as $key => $schedule) {
      $schedules_array[$key]['date'] = $schedule['date'];
      $schedules_array[$key]['time_from'] = $schedule['time_from'];
    }

    $message = 
      'Dear '.$user->full_name.':
      This is to confirm that you have booked a session:';
      foreach($schedules_array as $schedule_date){
        $message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
      };
    
    $teacher_message = 
      'Dear '.$teacher->full_name.':
      '.$user->full_name.' has booked a session with you:
      ';
      foreach($schedules_array as $schedule_date){
        $teacher_message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
      };

    $teacher->notify(new BookSession($teacher_message));
    $user->notify(new BookSessionStudent($message));

    return redirect()->route('front.profile.index');
    return response()->json(['sucess' => 'thank you'],200);   

  }


  public function fawryResponse(Request $request)
  {
      $response = json_decode($request->chargeResponse , 'Array');
      $order = $this->order->where('merchant_reference_number', $response['merchantRefNumber'])->first();

      if (!$order) {
        alert()->error(__('frontend.error_payment'), __('frontend.error'))->persistent('Close');
        return redirect()->to('/');
      }

      $this->user_enrolls->bookedFawry($order);
      alert()->success(__('frontend.success_fawry'), __('frontend.thanks'))->persistent('Close');

      $lectures = json_decode($order->lectures, 'Array');
      $lectures_array = [];
      foreach($lectures as $lecture){
          $lectures_array['lecture_id'] = $lecture['lecture_id'];
      }
      $lectures_ids = array_values($lectures_array);

      $schedules = $this->schedule->whereIn('lecture_id', $lectures_ids)->get();

      $schedules_array = [];
      foreach($schedules as $key => $schedule) {
        $schedules_array[$key]['date'] = $schedule['date'];
        $schedules_array[$key]['time_from'] = $schedule['time_from'];
      }

      $lectures_ids = array_values($lectures_array);
        $lecture_id = $this->lecture->whereIn('id', $lectures_ids)->pluck('id')->toArray();
        $lecture = $this->lecture->where('id', $lecture_id)->first();
        $teacher = $this->user->get($lecture->teacher_id);
        $user = $this->user->get($order->user_id);
  
        $message = 
        'Dear '.$user->full_name.':
        This is to confirm that you have selected a session:';
        foreach($schedules_array as $schedule_date){
          $message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
        };
      
        $teacher_message = 
        'Dear '.$teacher->full_name.':
        '.$user->full_name.' has selected a session with you:
        ';
        foreach($schedules_array as $schedule_date){
          $teacher_message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
        };
  
       $teacher->notify(new BookPendingSession($teacher_message));
        $user->notify(new BookPendingSessionStudent($message));
     
      return redirect()->to('/');
  }

  public function validateFawry(Request $request)
  {
      Log::info($request);

      $fawryResponse = $request;

      $merchantRefNumber = $fawryResponse['MerchantRefNo'];
      $fawryRefNumber = $fawryResponse['FawryRefNo'];
      $orderStatus = $fawryResponse['OrderStatus'];
      $amount = $fawryResponse['Amount'];
      $secureHashKey = 'c646c9acde9b411aa0364192079e257e';
      $messageSignature = $fawryResponse['MessageSignature'];

      $this->order->where('merchant_reference_number', $merchantRefNumber)->update([
        'fawry_response' => json_encode($request)
      ]);

      $order = $this->order->where('merchant_reference_number', $merchantRefNumber)->first();

      $responseData = $fawryRefNumber . $merchantRefNumber . $orderStatus  . $amount . $secureHashKey;
      $signature = md5($responseData);
      
      if (number_format($order['amount'], 2, '.', '') != $amount && $signature != $messageSignature) {
          return response()->json(['msg' => 'invalid Signature or Amount', 'code' => 400], 200);
      }

      $lectures = json_decode($order->lectures, 'Array');
      $lectures_array = [];
      foreach($lectures as $lecture){
          $lectures_array['lecture_id'] = $lecture['lecture_id'];
      }
      $lectures_ids = array_values($lectures_array);

      $schedules = $this->schedule->whereIn('lecture_id', $lectures_ids)->get();

      $schedules_array = [];
      foreach($schedules as $key => $schedule) {
        $schedules_array[$key]['date'] = $schedule['date'];
        $schedules_array[$key]['time_from'] = $schedule['time_from'];
      }
    
      if ($fawryResponse['OrderStatus'] == "PAID") {
          $this->schedule->whereIn('lecture_id', $lectures_ids)->update(['payed' => true, 'status' => 'success']);
          $order->update(["status" => "success"]);

          $lectures_ids = array_values($lectures_array);
          $lecture_id = $this->lecture->whereIn('id', $lectures_ids)->pluck('id')->toArray();
          $lecture = $this->lecture->where('id', $lecture_id)->first();
          $teacher = $this->user->get($lecture->teacher_id);
          $user = $this->user->get($order->user_id);
    
          $message = 
          'Dear '.$user->full_name.':
          This is to confirm that you have booked a session:';
          foreach($schedules_array as $schedule_date){
            $message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
          };
        
          $teacher_message = 
          'Dear '.$teacher->full_name.':
          '.$user->full_name.' has booked a session with you:
          ';
          foreach($schedules_array as $schedule_date){
            $teacher_message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
          };
    
         $teacher->notify(new BookSession($teacher_message));
         $user->notify(new BookSessionStudent($message));
      }

      if ($fawryResponse['OrderStatus'] == "EXPIRED" || $fawryResponse['OrderStatus'] == "CANCELED") {
        $this->schedule->whereIn('lecture_id', $lectures_ids)->delete();
        $order->update(["status" => $fawryResponse['OrderStatus']]);
        $user = $this->user->get($order->user_id);
        $message = 'Session has been '.$fawryResponse['OrderStatus'];
       $user->notify(new BookSession($message));
      }

      
      return response()->json(['msg' => 'Success', 'code' => 200], 200);
  }

  public function response(Request $request, $free = false)
  {
    $validatePayment = $this->payments->validate($request);

    if (!$validatePayment) {
      alert()->error(__('frontend.error_payment'), __('frontend.error'))->persistent('Close');
      return redirect()->to('/');
    }
    return self::callbackPayment($request);
  }


  public function callbackPayment($request)
  {
    $order = $this->order->where('unique_id', (int)$request->isysid)->first();

    if (!$order) {
      alert()->error(__('frontend.error_payment'), __('frontend.error'))->persistent('Close');
      return redirect()->to('/');
    }

    $this->user_enrolls->booked($order);
    alert()->success(__('frontend.success_checkout'), __('frontend.thanks'))->persistent('Close');

    $lectures = json_decode($order->lectures, 'Array');
    $lectures_array = [];
    foreach($lectures as $lectureItem){
      $lectures_array['lecture_id'] = $lectureItem['lecture_id'];
    }

    $lectures_ids = array_values($lectures_array);

    $schedules = $this->schedule->whereIn('lecture_id', $lectures_ids)->get();

    $schedules_array = [];
    foreach($schedules as $key => $schedule) {
      $schedules_array[$key]['date'] = $schedule['date'];
      $schedules_array[$key]['time_from'] = $schedule['time_from'];
    }

    $lecture_id = $this->lecture->whereIn('id', $lectures_ids)->pluck('id')->toArray();
    $lecture = $this->lecture->where('id', $lecture_id)->first();
    $teacher = $this->user->get($lecture->teacher_id);
    $user = $this->user->get(Auth::id());

    $message = 
    'Dear '.$user->full_name.':
    This is to confirm that you have booked a session:';
    foreach($schedules_array as $schedule_date){
      $message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
    };
  
    $teacher_message = 
    'Dear '.$teacher->full_name.':
    '.$user->full_name.' has booked a session with you:
    ';
    foreach($schedules_array as $schedule_date){
      $teacher_message.= $schedule_date['date'] .' at '. $schedule_date['time_from']."\r\n";
    };

   $teacher->notify(new BookSession($teacher_message));
   $user->notify(new BookSessionStudent($message));

    return redirect()->to('/');
  }


  public function getFaq()
  {
    return parent::view('faq', [
      "rows" => $this->faq->getAll()
    ]);
  }

  public function removeItem($id)
  {
    $this->user_enrolls->removeItem(Auth::id(), $id);
    return parent::redirectToIndex([
      "items" => $this->user_enrolls->getUnPaid(Auth::id())
    ]);
  }

  public function removeGroupItem($lecture_id)
  {
    $this->user_enrolls->removeGroupItem(Auth::id(), $lecture_id);
    return parent::redirectToIndex([
      "items" => $this->user_enrolls->getUnPaid(Auth::id())
    ]);
  }
}
