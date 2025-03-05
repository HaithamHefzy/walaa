<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NumberListResource;
use App\Traits\ApiResponse;
use App\Models\{NumberList,Number};
use App\Http\Requests\Number\NumberRequest;
use Maatwebsite\Excel\Facades\Excel;

class NumberListController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $lists = NumberList::paginate($request->limit ?? 10);

        return $this->successResponse(
            NumberListResource::collection($lists),
            'Numbers retrieved successfully'
        );
    }

    public function store(NumberRequest $numberRequest)
    {
        $list = NumberList::create([
            'title' => $numberRequest->title
        ]);

        $numbers = 
        $numberRequest->type == 'text' ?
        explode(',', $numberRequest->numbers)
        :
        Excel::toArray([],$numberRequest->file)[0];

        foreach($numbers as $number)
        {
            if (isset($number[0]) && !is_numeric($number[0])) {
                continue;
            }
            Number::create([
                'number_list_id' => $list->id,
                'number' => $numberRequest->type == 'file' ? $number[0] : $number
            ]);
        }

        return $this->successResponse(new NumberListResource($list), 'List created successfully', 201);
    }
}