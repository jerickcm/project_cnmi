<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogsRequest;
use App\Http\Resources\LogsResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Logs;
use App\Models\User;

class LogsController extends Controller
{
    public function index()
    {
        return LogsResource::collection(Logs::orderby('id', 'DESC')->get());
    }

    public function fetch(Request $request)
    {
        $user = User::where('email', Auth::guard('web')->user()->email)->first();
        $options = $request->options;
        $params = $request->params;
        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = Logs::query();


        $reqs  = $reqs->select('logs.*')->join('users', 'users.id', '=', 'logs.user_id');

        if ($user->admin == 0  &&  $user->superadmin == 0) {
            $reqs = $reqs->where('user_id', $user->id);
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['meta', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count = Logs::count();

        return response()->json([
            // 'superadmin' => $user->superadmin,
            // 'admin' => $user->admin,
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function store(LogsRequest $request)
    {
        $business = Logs::create($request->validated());
        return new LogsResource($business);
    }


    public function show(Logs $business)
    {
        return new LogsResource($business);
    }


    public function update(LogsRequest $request, Logs $business)
    {
        $business->update($request->validated());

        return new LogsResource($business);
    }

    public function destroy(Logs $business)
    {
        $business->delete();

        return response()->noContent();
    }

    public function search(LogsRequest $request)
    {
        $data = Logs::join('users', 'users.id', '=', 'logs.user_id')->where(function ($query) use ($request) {
            $word = str_replace(" ", " %", $request->searchValue);
            $query->where([['meta', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]]);
        })->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
