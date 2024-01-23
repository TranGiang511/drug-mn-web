<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        //index
        $results_per_page = $request->results_per_page ?? 5;
        if (isset($results_per_page) && $results_per_page !== 0) {
            // Search by name, phone, email
            if ($request->has('search')) {
                $search = $request->input('search');
                $data_search = DB::table('contact')
                    ->whereNull('deleted_at')
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%')
                            ->orWhere('phone', 'LIKE', '%' . $search . '%');
                    })
                    ->paginate($results_per_page)
                    ->withQueryString();

                return view('contact.index', [
                    'contacts' => $data_search->appends(['search' => $search]),
                ]);
            }

            $data = DB::table('contact')
                ->whereNull('deleted_at')
                ->paginate($results_per_page)
                ->withQueryString();

            return view('contact.index', [
                'contacts' => $data,
            ]);
        }
    }

    //create
    public function contact_submit(ContactRequest $request)
    {
        $data = DB::table('contact')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'content' => $request->message,
        ]);
        if ($data) {
            return redirect('/home')->with('success_contact', 'Gửi contact thành công!');
        }
    }

    //delete mutil
    public function delete_mutil(Request $request)
    {
        $auth_user = Auth::user();
        $ids = $request->check_item;
        if (is_array($ids) && $auth_user->role == 'admin') {
            $contacts = DB::table('contact')
                ->whereIn('id', $ids)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                ]);
            return redirect('/contact')->with('success', 'Đã xóa thành công liên hệ đã chọn');
        } else {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn xóa!'
            ]);
        }
    }

    //delete a contact by id
    public function destroy($id)
    {
        $auth_user = Auth::user();

        if ($auth_user->role == 'admin') {
            $data = DB::table('contact')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                        'deleted_at' => now(),
                    ]);

            if ($data) {
                return redirect('/contact')->with('success', 'Liên hệ được xóa thành công.');
            } else {
                return redirect()->back()->with('error', 'Không thể tìm thấy liên hệ để xóa.');
            }
        } else {
            abort(404);
        }
    }

    public function show($id)
    {
        $auth_user = Auth::user();

        if ($auth_user && $auth_user->role == 'admin') {
            $data = DB::table('contact')
                ->select('*')
                ->where('id', $id)
                ->first();
            DB::table('contact')
                ->where('id', $id)
                ->update([
                        'viewing_status' => 1,
                    ]);
            return view('contact.show', [
                'contact' => $data,
            ]);
        }

        abort(404);
    }

}
