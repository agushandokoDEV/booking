<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Ramsey\Uuid\Uuid as GeneratorUuid;
use Carbon\Carbon;
use App\Models\BookingList;

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

    public function create(Request $request)
    {
        $request->validate(
            [
                'available'=>'required|array',
                'available.*' => 'required|distinct|date_format:Y-m-d H:i'
            ]
        );

        $list=[];
        $available = $request->available;
        $check = BookingList::whereIn('available', $available)->get();
        $exist=[];
        if(count($check) > 0)
        {
            foreach ($check as $val)
            {
                $exist[]=$val->available;
            }
            return $this->errorResponse('Tanggal sudah tersedia ' . implode(",", $exist), 400);
        }

        foreach ($available as $item)
        {
            $list []=array(
                'id' => GeneratorUuid::uuid4()->toString(),
                'available'=>$item,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            );
        }

        BookingList::insert($list);

        return $this->successResponse($list);
    }

    public function update(Request $request,$id)
    {
        $request->validate(
            [
                'available' => 'required|date_format:Y-m-d H:i'
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

        $data->available=$request->available;
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
