<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Events\UserLogsEvent;

use App\Models\Logs;
use App\Models\User;
use App\Models\Dol_Employer_Notes;
use App\Models\ClearanceNote;
use App\Models\Company;

use App\Http\Requests\NotesClearanceRequest;


class NotesController extends Controller
{

    public function delete_note(Request $request, $id)
    {
        $note = Dol_Employer_Notes::findOrfail($id);

        $employer = User::where('id', $note->employer_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);
        // log start
        event(new UserLogsEvent($user->id, Logs::TYPE_DELETE_NOTE, [
            'id'  => $user->id,
            'email' => $user->email,
            'delete_id' =>   $note->id,
            'employer_id' => $employer->employer_id,
            'employer_name' =>  $employer->full_name,
            'employer_email' =>  $employer->email,
        ]));

        $note->delete();

        return response()->json([
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true,
        ]);
    }

    public function delete_note_clearance(Request $request, $id)
    {

        $note = ClearanceNote::findOrfail($id);
        $company = Company::where('id', $note->company_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        event(new UserLogsEvent($user->id, Logs::TYPE_DELETE_NOTE_CLEARANCE, [
            'id'  => $user->id,
            'email' => $user->email,
            'delete_id' =>   $note->id,
            'company_id' => $company->employer_id,
            'company_name' => $company->employer_name,
        ]));

        $note->delete();

        return response()->json([
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true,
        ]);

    }

    public function get_notes(Request $request, $employer_id)
    {


        $data = Dol_Employer_Notes::select('dol_employer_notes.*', 'users.full_name')
            ->where('employer_id', $employer_id)
            ->join('users', 'users.id', '=', 'dol_employer_notes.user_id')
            ->get();

        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function get_notes_clearance(Request $request, $company_id)
    {

        $data = ClearanceNote::select('clearance_notes.*', 'users.full_name')
            ->where('clearance_notes.company_id', $company_id)
            ->join('users', 'users.id', '=', 'clearance_notes.user_id')
            ->get();

        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function store_note(Request $request)
    {

        Validator::make($request->all(), [
            'note' => ['nullable'],
            'employer_id' => ['nullable'],
        ])->validate();

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        $create = Dol_Employer_Notes::create([
            'user_id' => $user->id,
            'employer_id' => $request->employer_id,
            'note' => $request->note,
        ]);

        $employer = User::where('id', $request->employer_id)->first();
        // log start
        event(new UserLogsEvent($user->id, Logs::TYPE_CREATE_NOTE, [
            'id'  => $user->id,
            'email' => $user->email,
            'create_id' => $create->id,
            'employer_id' => $request->employer_id,
            'employer_name' =>  $employer->full_name,
            'employer_email' =>  $employer->email,
        ]));
        // log end


        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function store_note_clearance(NotesClearanceRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrfail(Auth::guard('web')->user()->id);

            $fields_validate = $request->validated();
            $clearancenote = ClearanceNote::create($fields_validate);
            $clearancenote->user_id = $user->id;
            $clearancenote->save();

            $company = Company::where('id', $request->company_id)->first();

            // log start
            event(new UserLogsEvent($user->id, Logs::TYPE_CREATE_NOTE_CLEARANCE, [
                'id'  => $user->id,
                'email' => $user->email,
                'create_id' => $clearancenote->id,
                'company_id' => $request->company_id,
                'company_name' =>  $company->company_name,
            ]));
            // log end

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }
}
