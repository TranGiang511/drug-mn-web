<?php

namespace App\Http\Controllers;

use App\Http\Requests\InfoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class InfoController extends Controller
{
    // Admin
    public function index(Request $request)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            //index
            $results_per_page = $request->results_per_page ?? 5;
            if (isset($results_per_page) && $results_per_page !== 0) {
                // Search by title
                if ($request->has('search')) {
                    $search = $request->input('search');
                    $data_search = DB::table('info')
                        ->whereNull('deleted_at')
                        ->where('title', 'LIKE', '%' . $search . '%')
                        ->paginate($results_per_page)
                        ->withQueryString();

                    return view('info.index', [
                        'infos' => $data_search->appends(['search' => $search]),
                    ]);
                }

                $data = DB::table('info')
                    ->whereNull('deleted_at')
                    ->paginate($results_per_page)
                    ->withQueryString();

                return view('info.index', [
                    'infos' => $data,
                ]);
            }
        }

        abort(404);
    }

    public function create()
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            return view('info.create');
        }

        abort(404);
    }

    public function store(InfoRequest $request)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            $existingType = DB::table('info')
                ->where('type', Str::slug($request->title))
                ->exists();
    
            if ($existingType) {
                return redirect('/mn_info')->with('error', 'Loại thông tin đã tồn tại!');
            }
    
            $data = DB::table('info')
                ->insert([
                    'type' => Str::slug($request->title),
                    'title' => $request->title,
                    'title_e' => $request->title_e,
                    'content' => $request->content,
                    'content_e' => $request->content_e,
                ]);
            
            if ($data) {
                return redirect('/mn_info')->with('success', 'Tạo thông tin thành công!');
            }
        }
    
        abort(404);
    }    

    public function show($id)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            $data = DB::table('info')
                ->select('*')
                ->where('id', $id)
                ->first();
            return view('info.show', [
                'info' => $data,
            ]);
        }

        abort(404);
    }

    public function edit($id)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            $data = DB::table('info')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            return view('info.edit', [
                'info' => $data
            ]);
        }

        abort(404);
    }

    public function update(InfoRequest $request, $id)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            $existingType = DB::table('info')
                ->where('type', Str::slug($request->title))
                ->where('id', '!=', $id) // Loại trường hợp kiểm tra ngoại trừ bản ghi hiện tại
                ->whereNull('deleted_at')
                ->exists();
    
            if ($existingType) {
                return redirect('/mn_info')->with('error', 'Loại thông tin đã tồn tại!');
            }
    
            DB::table('info')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'type' => Str::slug($request->title),
                    'title' => $request->title,
                    'title_e' => $request->title_e,
                    'content' => $request->content,
                    'content_e' => $request->content_e,
                ]);
            
            return redirect('/mn_info')->with('success', 'Thông tin được cập nhật thành công.');
        } 
    
        abort(404);
    }
    
    public function destroy($id)
    {
        $auth_user = Auth::user();
        if ($auth_user && $auth_user->role == 'admin') {
            $data = DB::table('info')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                ]);

            if ($data) {
                return redirect('/mn_info')->with('success', 'Thông tin được xóa thành công.');
            } else {
                return redirect('/mn_info')->with('error', 'Không thể tìm thấy thông tin để xóa.');
            }
        } 

        abort(404);
    }
    
    public function type($type)
    {
        // check locale
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        $infos = null;
        $infoOfType = DB::table('info')
            ->where('type', $type)
            ->first();
        if ($locale == 'en') {
            $infos = DB::table('info')
                ->select('id', 'type', 'title_e', 'content_e', 'created_at', 'updated_at', 'deleted_at')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $infos = DB::table('info')
                ->select('id', 'type', 'title', 'content', 'created_at', 'updated_at', 'deleted_at')
                ->whereNull('deleted_at')
                ->get();
        }
        return view ('info.show_home', compact('infoOfType', 'infos', 'locale'));
    }
}
