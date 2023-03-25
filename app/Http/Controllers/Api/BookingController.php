<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingList;
use App\Models\BookingListTime;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Booking;

class BookingController extends Controller
{
    use ApiResponse;

    public function booked(Request $request)
    {
        $request->validate([
            'bookinglisttime_id'=>'required|uuid',
            'name'=>'required',
            'email'=>'required|email'
        ]);

        $bookinglisttime_id=$request->bookinglisttime_id;
        $cek=Booking::where('bookinglisttime_id',$bookinglisttime_id)
            ->where('confirm','Y')
            ->exists();


        if($cek)
        {
            return $this->errorResponse('Sudah ada yang memesan/booked');
        }

        $data=Booking::create([
            'id' => GeneratorUuid::uuid4()->toString(),
            'bookinglisttime_id'=>$bookinglisttime_id,
            'booking_name'=>$request->name,
            'email' => $request->email,
            'token'=>Str::random(50),
            'token_exp'=> Carbon::now()->addMinute(30),
            'message'=>$request->message
        ]);

        return $this->successResponse($data);
    }
    public function checkByDate(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $data = BookingList::with(['listtime','listtime.allbooking','listtime.booked'])
            ->whereDate('available_at', $date)
            ->first();

        return $this->successResponse($data);
    }

    public function bookedCheckByMonth(Request $request)
    {
        $year = Carbon::parse($request->date)->format('Y');
        $month = Carbon::parse($request->date)->format('m');
        $data=BookingList::whereYear('available_at',$year)
            ->whereMonth('available_at',$month)
            ->orderBy('available_at','ASC')
            ->get();
        $list=[];
        if($data)
        {
            foreach ($data as $item)
            {
                $list[]=array(
                    'id'=>$item->id,
                    'year' => (int)Carbon::parse($item->available_at)->format('Y'),
                    'month' => (int)Carbon::parse($item->available_at)->format('m'),
                    'day'=> (int)Carbon::parse($item->available_at)->format('d'),
                    'fulldate'=> Carbon::parse($item->available_at)->format('Y-m-d'),
                    'status' => $item->status,
                    'can_book' => $item->can_book
                );
            }
        }
        return $this->successResponse($list);
    }

    public function bookedListTime(Request $request)
    {
        $bookinglist_id=$request->bookinglist_id;
        $data=BookingListTime::where('bookinglist_id',$bookinglist_id)
            ->orderBy('available_at', 'ASC')
            ->get();

        $list = [];
        if ($data) {
            foreach ($data as $item)
            {
                $list[] = array(
                    'id' => $item->id,
                    'bookinglist_id' => $item->bookinglist_id,
                    'available_at' => Carbon::parse($item->available_at)->format('H:i'),
                    'status' => $item->status,
                    'can_book' => $item->can_book
                );
            }
        }
        return $this->successResponse($list);
    }

    public function bookedCheckbyToken(Request $request, $token)
    {
        // $date = Carbon::parse($request->date)->format('Y-m-d');
        $data = Booking::where('confirm', 'N')
            ->where('token', $token)
            ->first();

        return $this->successResponse($data);
    }

    public function bookConfirm(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'bookinglisttime_id' => 'required|uuid',
            'token' => 'required|size:50',
            'email' => 'required|email'
        ]);

        $cek_book = Booking::where('confirm', 'Y')
            ->where('bookinglisttime_id', $request->id)
            ->exists();

        if ($cek_book)
        {
            return $this->errorResponse('Sudah ada yang memesan/booking', 400);
        }

        $data = Booking::where('email', $request->email)
            ->where('bookinglisttime_id', $request->bookinglisttime_id)
            ->where('token', $request->token)
            ->first();

        if (!$data) {
            return $this->notFoundResponse('Data tidak tersedia', 400);
        }

        // if ($data->token_exp <= Carbon::now())
        // {
        //     return $this->errorResponse('Token kadaluarsa', 400);
        // }

        $data->confirm = 'Y';
        $data->token = null;
        $data->token_exp=null;
        $data->save();

        $book=BookingListTime::find($request->bookinglisttime_id);
        $book->status='booked';
        $book->save();

        return $this->successResponse(null);
    }
}
