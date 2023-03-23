<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'bookinglist_id'=>'required|uuid',
            'name'=>'required',
            'email'=>'required|email'
        ]);

        $bookinglist_id=$request->bookinglist_id;
        $cek=Booking::where('bookinglist_id',$bookinglist_id)->where('confirm','Y')->exists();
        if($cek)
        {
            return $this->errorResponse('Sudah ada yang memesan/booked');
        }

        $data=Booking::create([
            'id' => GeneratorUuid::uuid4()->toString(),
            'bookinglist_id'=>$bookinglist_id,
            'booking_name'=>$request->name,
            'email' => $request->email,
            'token'=>Str::random(50),
            'token_exp'=> Carbon::now()->addMinute(3),
            'message'=>$request->message
        ]);

        return $this->successResponse($data);
    }

    public function bookedCheck(Request $request,$id)
    {
        // $date = Carbon::parse($request->date)->format('Y-m-d');
        $data=Booking::where('confirm','Y')
            ->where('bookinglist_id',$id)
            // ->whereDate('available',$date)
            ->first();

        return $this->successResponse($data);
    }

    public function bookConfirm(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'token' => 'required|size:50',
            'email' => 'required|email'
        ]);

        $cek_book = Booking::where('confirm', 'Y')
            ->where('bookinglist_id', $request->id)
            ->exists();

        if ($cek_book)
        {
            return $this->errorResponse('Sudah ada yang memesan/booking', 400);
        }

        $data = Booking::where('email', $request->email)
            ->where('bookinglist_id', $request->id)
            ->where('token', $request->token)
            ->first();

        if (!$data) {
            return $this->notFoundResponse('Data tidak tersedia', 400);
        }

        if ($data->token_exp <= Carbon::now())
        {
            return $this->errorResponse('Token kadaluarsa', 400);
        }

        $data->confirm='Y';
        $data->save();

        return $this->successResponse($data);
    }
}
