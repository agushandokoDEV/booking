<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use Carbon\Carbon;
use App\Models\BookingList;
use App\Models\BookingListTime;

class BookingListController extends Controller
{
    use ApiResponse;

    public function list(Request $request)
    {

        $list = BookingList::all();
        if($request->has('status'))
        {
            $list = BookingList::where('status',$request->status)->get();
        }
        return $this->successResponse($list);
    }

    public function byDate(Request $request)
    {
        $date= Carbon::parse($request->date)->format('Y-m-d');
        $list = BookingList::whereDate('available',$date);
        if ($request->has('status')) {
            $list->where('status',$request->status);
        }
        $data=$list->get();
        return $this->successResponse($data);
    }

    public function byID(Request $request,$id)
    {
        $data = BookingList::with(['booked','allbooking'])->find($id);
        return $this->successResponse($data);
    }

    // public function create(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'available'=>'required|array',
    //             'available.*' => 'required|distinct|date_format:Y-m-d H:i'
    //         ]
    //     );

    //     $list=[];
    //     $available = $request->available;
    //     $check = BookingList::whereIn('available', $available)->get();
    //     $exist=[];
    //     if(count($check) > 0)
    //     {
    //         foreach ($check as $val)
    //         {
    //             $exist[]=$val->available;
    //         }
    //         return $this->errorResponse('Tanggal sudah tersedia ' . implode(",", $exist), 400);
    //     }

    //     foreach ($available as $item)
    //     {
    //         $list []=array(
    //             'id' => GeneratorUuid::uuid4()->toString(),
    //             'available'=>$item,
    //             'created_at' => Carbon::now()->toDateTimeString(),
    //             'updated_at' => Carbon::now()->toDateTimeString()
    //         );
    //     }

    //     BookingList::insert($list);

    //     return $this->successResponse($list);
    // }

    public function createByMonth(Request $request)
    {
        $request->validate(
            [
                'month'=>'required|date_format:Y-m',
                'time' => 'required|array',
                'time.*' => 'required|distinct|date_format:H:i'
            ]
        );

        $bookinglist=[];
        $bookinglist_time=[];
        $list_available_time=[];
        $month = $request->month;
        $time=$request->time;
        $last_day=(int)Carbon::parse($month)->endOfMonth()->format('d');
        for ($i=1; $i <= $last_day; $i++)
        {
            $available = Carbon::parse($month . '-' . $i)->format('Y-m-d');
            $bookinglist_id=GeneratorUuid::uuid4()->toString();
            $list_available_time[] = $available;
            $bookinglist[] = array(
                'id' => $bookinglist_id,
                'available_at' => $available,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            );

            if (count($time) > 0)
            {
                foreach ($time as $time_item)
                {
                    $bookinglist_time[] = array(
                        'id' => GeneratorUuid::uuid4()->toString(),
                        'bookinglist_id' => $bookinglist_id,
                        'available_at' => $time_item,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    );
                }
            }
        }

        $cek_exist=BookingList::whereIn('available_at',$list_available_time)->exists();
        if($cek_exist)
        {
            return $this->errorResponse('Booking list already exist');
        }
        BookingList::insert($bookinglist);
        BookingListTime::insert($bookinglist_time);

        // BookingList::insert($list);
        return $this->successResponse(null);
    }

    public function update(Request $request,$id)
    {
        $request->validate(
            [
                'available' => 'required|date_format:Y-m-d H:i',
                'can_book'=>'required|boolean'
            ]
        );
        $data = BookingList::find($id);
        $available = $request->available;
        if(!$data)
        {
            return $this->notFoundResponse('Data tidak ditemukan');
        }
        $check = BookingList::where('available', $available)->where('id','!=',$id)->exists();
        if($check)
        {
            return $this->errorResponse('Data sudah tersedia',400);
        }

        $data->can_book = $request->can_book;
        $data->status=$request->can_book;
        // $data->available_at=$request->available;
        $data->save();

        return $this->successResponse(null);
    }

    public function remove(Request $request,$id)
    {
        $data = BookingList::find($id);
        if (!$data) {
            return $this->notFoundResponse('Data tidak ditemukan');
        }
        $data->delete();
        return $this->successResponse(null);
    }
}
