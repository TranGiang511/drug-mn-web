<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NewRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class NewController extends Controller
{
    public function index(Request $request)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');

        if (!in_array($locale, $allowedLocales)) {
            $countPost = 0;
            $news = null;
        } else {
            $results_per_page = $request->results_per_page ?? 5;
            $query = DB::table(($locale === 'en') ? 'news_tran' : 'news')
                ->join('users', 'users.id', '=', ($locale === 'en') ? 'news_tran.id_user' : 'news.id_user')
                ->select(($locale === 'en') ? 'news_tran.*' : 'news.*', 'users.name', 'users.role')
                ->whereNull(($locale === 'en') ? 'news_tran.deleted_at' : 'news.deleted_at')
                ->where('public_check', '=', 1)
                ->where('public_date', '<=', Carbon::now());
            if ($locale === 'en') {
                if (isset($request->search)) {
                    $query->where('news_tran.title', 'LIKE', "%" . $request->search . "%");
                }
            } elseif ($locale === 'vi') {
                if (isset($request->search)) {
                    $query->where('news.title', 'LIKE', "%" . $request->search . "%");
                }
            } else {
                $query = null;
            }
            $countPost = $query->count();
            $news = $query->latest()->paginate($results_per_page);
        }
        return view('news.index', [
            'news' => $news,
            'countPost' => $countPost,
            'locale' => $locale,
            'infos' => $infos,
        ]);
    }

    public function create()
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        return view('news.create', [
            'infos' => $infos,
        ]);
    }

    public function store(NewRequest $request)
    {
        $user = Auth::user();
        if (!$request->filled('lang') || !in_array($request->lang, ['vi'])) {
            return redirect()->back()->withErrors(['lang' => 'Ngôn ngữ không hợp lệ.'])->withInput();
        }
        $filename = '';
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('uploads/post/thumbnail', $filename);
            } else {
                return "Lỗi khi tải tệp lên: " . $file->getErrorMessage();
            }
        }
        $public_check = $user->role == "admin" ? 1 : 0;
        $banner = $request->input('banner', 0);
        try {
            DB::beginTransaction();
            $news = [
                'id_user' => $user->id,
                'title' => $request->title,
                'thumbnail' => $filename,
                'banner' => $banner,
                'content' => $request->content,
                'public_date' => $request->public_date,
                'public_check' => $public_check,
            ];
            $language = $request->input('lang');
            if ($language == 'vi') {
                DB::table('news')->insertGetId($news);
            }
            DB::commit();
            return redirect('/news')->with('success', 'Thêm tin tức thành công.!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm tin tức!');
        }
    }

    public function show($id, Request $request)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (!in_array($locale, $allowedLocales)) {
            return view('news.show', [
                'new' => null,
            ]);
        }
        $tableName = ($locale === 'en') ? 'news_tran' : 'news';
        $query = DB::table($tableName)
            ->join('users', 'users.id', '=', $tableName . '.id_user')
            ->select($tableName . '.*', 'users.name')
            ->whereNull($tableName . '.deleted_at')
            ->where('public_check', '=', 1)
            ->where('public_date', '<=', Carbon::now());
        if ($locale === 'en') {
            $query->where($tableName . '.id_news', '=', $id);
        } elseif ($locale === 'vi') {
            $query->where($tableName . '.id', '=', $id);
        }
        $new = $query->first();
        return view('news.show', [
            'new' => $new,
            'locale' => $locale,
            'infos' => $infos,
        ]);
    }

    public function destroy($id)
    {
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (in_array($locale, $allowedLocales)) {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';
            $query = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '=', 1)
                ->where('public_date', '<=', Carbon::now());
            if ($locale === 'en') {
                $query->where($tableName . '.id_news', '=', $id);
            } elseif ($locale === 'vi') {
                $query->where($tableName . '.id', '=', $id);
            }
            $new = $query->first();
            if (!$new) {
                abort(404);
            } else {
                if (Auth::check()) {
                    $user = Auth::user();
                    // check permisssion
                    if ($user->role == 'admin' && ($new->id_user == $user->id || $new->role != 'admin') || ($new->role != 'admin' && $new->id_user == $user->id)) {
                        $query = DB::table($tableName);
                        if ($locale === 'en') {
                            $query->where($tableName . '.id_news', '=', $id);
                        } elseif ($locale === 'vi') {
                            $query->where($tableName . '.id', '=', $id);
                        }
                        $query->update([
                            $tableName . '.deleted_at' => now(),
                        ]);
                        return redirect("/news")->with('success', 'Đã xóa thành công bài viết!');
                    } else {
                        return redirect()->back()->with('error', 'Không có quyền xóa bài viết này!');
                    }
                }
            }
        }
    }

    public function self_edit($id)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (!in_array($locale, $allowedLocales)) {
            return view('news.edit', [
                'new' => null,
            ]);
        } else {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';
            $query = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '=', 1)
                ->where('public_date', '<=', Carbon::now());
            if ($locale === 'en') {
                $query->where($tableName . '.id_news', '=', $id);
            } elseif ($locale === 'vi') {
                $query->where($tableName . '.id', '=', $id);
            }
            $new = $query->first();
            if (!$new) {
                abort(404);
            } else {
                if (Auth::check()) {
                    $user = Auth::user();
                    // check permisssion
                    if (
                        $user->role == 'admin' && ($new->id_user == $user->id || $new->role != 'admin') ||
                        ($new->role != 'admin' && $new->id_user == $user->id)
                    ) {
                        return view('news.edit', [
                            'new' => $new,
                            'locale' => $locale,
                            'infos' => $infos,
                        ]);
                    } else {
                        return redirect()->back()->with('error', 'Không có quyền chỉnh sửa bài viết này!');
                    }
                }
            }
        }
    }

    public function update(UpdateNewRequest $request, $id)
    {
        // $data = $request->all();
        // dd($data);
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (in_array($locale, $allowedLocales)) {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';
            $query = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '=', 1)
                ->where('public_date', '<=', Carbon::now());
            if ($locale === 'en') {
                $query->where($tableName . '.id_news', '=', $id);
            } elseif ($locale === 'vi') {
                $query->where($tableName . '.id', '=', $id);
            }
            $new = $query->first();
            if (!$new) {
                abort(404);
            } else {
                if (Auth::check()) {
                    $user = Auth::user();
                    // check permisssion
                    if ($user->role == 'admin' && ($new->id_user == $user->id || $new->role != 'admin') || ($new->role != 'admin' && $new->id_user == $user->id)) {
                        if ($filename = $request->file('thumbnail')) {
                            $file = $request->file('thumbnail');
                            $ext = $file->getClientOriginalExtension();
                            $filename = time() . '.' . $ext;
                            $file->move('uploads/post/thumbnail', $filename);
                        } else {
                            $filename = $new->thumbnail;
                        }

                        $query = DB::table($tableName);
                        if ($locale === 'en') {
                            $query->where($tableName . '.id_news', '=', $id);
                        } elseif ($locale === 'vi') {
                            $query->where($tableName . '.id', '=', $id);
                        }

                        $query->update([
                            'title' => $request->input('title'),
                            'thumbnail' => $filename,
                            'content' => $request->input('content'),
                            'updated_at' => now(),
                        ]);
                        return redirect("/news")->with('success', 'Đã cập nhật thành công bài viết!');
                    } else {
                        return redirect()->back()->with('error', 'Không có quyền chỉnh sửa bài viết này!');
                    }
                }
            }
        }
    }

    public function delete_mutil(Request $request)
    {
        $ids = $request->check_item;
    
        if (!is_array($ids) || count($ids) == 0) {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn xóa'
            ]);
        }
    
        $auth_user = Auth::user();
    
        if (!$auth_user || $auth_user->role !== 'admin') {
            return redirect()->back()->withErrors([
                'error_permission' => 'Bạn không có quyền thực hiện tác vụ này.'
            ]);
        }
    
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
    
        if (in_array($locale, $allowedLocales)) {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';
            $query = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '=', 1)
                ->where('public_date', '<=', Carbon::now());
    
            if ($locale == 'en') {
                $query->whereIn($tableName . '.id_news', $ids); 
            } elseif ($locale == 'vi') {
                $query->whereIn($tableName . '.id', $ids); 
            }
    
            $records = $query->get();
    
            if ($records->isEmpty()) {
                abort(404);
            } else {
                foreach ($records as $record) {
                    if ($auth_user->role == 'admin' && ($record->id_user == $auth_user->id || $record->role != 'admin') || ($record->role != 'admin' && $record->id_user == $auth_user->id)) {
                        DB::table($tableName)
                            ->where($tableName . '.id', $record->id)
                            ->update([
                                'deleted_at' => now(),
                            ]);
                    }
                }
                return redirect()->back()->with('success', 'Các bài viết đã được xóa thành công.');
            }
        }
    }    
    
    //quản lý user (chỉ dành cho admin)
    function mn_users_index(Request $request)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $auth_user = Auth::user();
        if ($auth_user->role == 'admin') {
            $results_per_page = $request->results_per_page ?? 5;
            //search news of user
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $countPost = DB::table('news')
                    ->join('users', 'users.id', '=', 'news.id_user')
                    ->select('news.*', 'users.name', 'users.role')
                    ->where('role', '=', 'user')
                    ->whereNull('news.deleted_at')
                    ->count();
                $news_users = DB::table('news')
                    ->join('users', 'users.id', '=', 'news.id_user')
                    ->select('news.*', 'users.name', 'users.role')
                    ->where('role', '=', 'user')
                    ->whereNull('news.deleted_at')
                    ->where('news.title', 'LIKE', "%" . $search . "%")
                    ->latest()
                    ->paginate($results_per_page);
                return view('news.mn_users.index', [
                    'news_users' => $news_users,
                    'countPost' => $countPost,
                    'infos' => $infos,
                ]);
            }

            //full news of user
            $news_users = DB::table('news')
                ->join('users', 'users.id', '=', 'news.id_user')
                ->select('news.*', 'users.name', 'users.role')
                ->where('role', '=', 'user')
                ->whereNull('news.deleted_at')
                ->latest()
                ->paginate($results_per_page);
            $countPost = DB::table('news')
                ->join('users', 'users.id', '=', 'news.id_user')
                ->select('news.*', 'users.name', 'users.role')
                ->where('role', '=', 'user')
                ->whereNull('news.deleted_at')
                ->count();
            return view('news.mn_users.index', [
                'news_users' => $news_users,
                'countPost' => $countPost,
                'infos' => $infos,
            ]);
        }
    }

    public function news_mn_user_show($id) 
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $new = DB::table('news')->where('id', $id)->first();
        if ($new == null) {
            return abort(404);
        }
        $data_vi = DB::table('news')
            ->join('users', 'users.id', '=', 'news.id_user')
            ->select('news.*', 'news.id as id', 'users.name', 'users.role')
            ->where('news.id', $id)
            ->whereNull('news.deleted_at') 
            ->where('news.public_date', '<=', Carbon::now())
            ->first();
    
        $data_en = DB::table('news_tran')
            ->join('users', 'users.id', '=', 'news_tran.id_user')
            ->join('news', 'news_tran.id_news', '=', 'news.id')
            ->select('news_tran.*', 'news.id as id', 'news_tran.thumbnail', 'users.name', 'users.role')
            ->where('news_tran.id_news', $id)
            ->whereNull('news.deleted_at') 
            ->where('news_tran.public_date', '<=', Carbon::now())
            ->first();
    
        if (Auth::check() && Auth::user()->role == 'admin') {
            return view('news.mn_users.show', [
                'new' => $data_vi, 
                'new_tran' => $data_en, 
                'id' => $id,
                'infos' => $infos,
            ]);
        } else {
            return redirect()->back()->with('error', 'Không có quyền xem bài viết này!');
        }
    }

    public function public ($id)
    {
        $auth_user = Auth::user();
        if ($auth_user->role == 'admin') {
            DB::table('news')->where('id', $id)->update([
                'public_check' => 1,
            ]);
            return redirect()->back();
        }
    }

    function news_delete_mutil_users(Request $request)
    {
        $ids = $request->check_item;
        if (is_array($ids)) {
            DB::table('news')->whereIn('id', $ids)->update([
                'deleted_at' => now(),
            ]);
        }
        return redirect()->back();
    }

    public function news_banner_mutil(Request $request)
    {
        $ids = $request->check_item;
        if (is_array($ids)) {
            DB::table("news")->whereIn('id', $ids)->update([
                'banner' => 1,
            ]);
            return redirect()->back()->with('success', 'Đã banner thành công!');
        } else {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn banner'
            ]);
        }
    }

    public function news_public_mutil(Request $request)
    {
        $ids = $request->check_item;
        if (is_array($ids)) {
            DB::table("news")->whereIn('id', $ids)->update([
                'public_check' => 1,
            ]);
            return redirect()->back()->with('success', 'Đã public thành công!');
        } else {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn public!'
            ]);
        }
    }

    //quản lý tin tức cá nhân
    function news_auth_index(Request $request)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $query = DB::table('news')
            ->join('users', 'users.id', '=', 'news.id_user')
            ->select('news.*', 'users.name', 'users.role')
            ->where('news.id_user', '=', Auth::user()->id)
            ->whereNull('news.deleted_at');
        // Bảo toàn truy vấn tìm kiếm nếu có từ input search
        $search = $request->input('search');
        if ($search !== null) {
            $query->where('news' . '.title', 'LIKE', "%$search%");
        }
        $results_per_page = $request->results_per_page ?? 5;
        $countPost = $query->count();
        $news_auth = $query->latest()->paginate($results_per_page)->withQueryString();

        // Kiểm tra nếu trang hiện tại vượt quá số trang tồn tại
        if ($request->input('page') > $news_auth->lastPage()) {
            return Redirect::to($news_auth->url(1))->withInput($request->all());
        }
        return view('news.auth.index', [
            'news_auth' => $news_auth
                ->appends(['search' => $search])
                ->appends($request->query()),
            'countPost' => $countPost,
            'infos' => $infos,
        ]); 
    }

    public function news_auth_show($id)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $new_auth = DB::table('news')->where('id', $id)->first();
        if ($new_auth == null) {
            return abort(404);
        }
        $data_vi = DB::table('news')
            ->join('users', 'users.id', '=', 'news.id_user')
            ->select('news.*', 'news.id as id', 'users.name', 'users.role')
            ->where('news.id', $id)
            ->whereNull('news.deleted_at') 
            ->where('news.public_check', '=', 1)
            ->where('news.public_date', '<=', Carbon::now())
            ->first();
    
        $data_en = DB::table('news_tran')
            ->join('users', 'users.id', '=', 'news_tran.id_user')
            ->join('news', 'news_tran.id_news', '=', 'news.id')
            ->select('news_tran.*', 'news.id as id', 'news_tran.thumbnail', 'users.name', 'users.role')
            ->where('news_tran.id_news', $id)
            ->whereNull('news.deleted_at') 
            ->where('news_tran.public_check', '=', 1)
            ->where('news_tran.public_date', '<=', Carbon::now())
            ->first();
    
        if (Auth::check()) {
            // check permission
            if ($new_auth->id_user == Auth::id()) {
                return view('news.auth.show', [
                    'new' => $data_vi, 
                    'new_tran' => $data_en, 
                    'id' => $id,
                    'infos' => $infos,
                ]);
            } else {
                return redirect()->back()->with('error', 'Không có quyền xem bài viết này!');
            }
        } 
    }

    public function news_auth_edit($id, Request $request)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        $new_auth = DB::table('news')->where('id', $id)->first();
        if ($new_auth == null) {
            return abort(404);
        }
        $data_vi = DB::table('news')
            ->join('users', 'users.id', '=', 'news.id_user')
            ->select('news.*', 'news.id as id', 'users.name', 'users.role')
            ->where('news.id', $id)
            ->whereNull('news.deleted_at') 
            ->where('news.public_check', '=', 1)
            ->where('news.public_date', '<=', Carbon::now())
            ->first();
    
        $data_en = DB::table('news_tran')
            ->join('users', 'users.id', '=', 'news_tran.id_user')
            ->join('news', 'news_tran.id_news', '=', 'news.id')
            ->select('news_tran.*', 'news.id as id', 'news_tran.thumbnail', 'users.name', 'users.role')
            ->where('news_tran.id_news', $id)
            ->whereNull('news.deleted_at') 
            ->where('news_tran.public_check', '=', 1)
            ->where('news_tran.public_date', '<=', Carbon::now())
            ->first();
    
        if (Auth::check()) {
            // check permission
            if ($new_auth->id_user == Auth::id()) {
                return view('news.auth.edit', [
                    'new' => $data_vi, 
                    'new_tran' => $data_en, 
                    'id' => $id,
                    'infos' => $infos,
                ]);
            } else {
                return redirect()->back()->with('error', 'Không có quyền chỉnh sửa bài viết này!');
            }
        } 
    }
    
    public function news_auth_update_vi(UpdateNewRequest $request, $id)
    {
        // Kiểm tra xem có file thumbnail được tải lên không
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/post/thumbnail', $filename);
        } else {
            // Nếu không có file mới, giữ nguyên tên file cũ
            $filename = DB::table('news')->where('id', $id)->value('thumbnail');
        }
        // Kiểm tra xem có bản ghi với ID cung cấp không
        $new_auth = DB::table('news')->where('id', $id)->first();
        if (!$new_auth) {
            abort(404);
        } else {
            // Định dạng ngày tháng cho 'public_date'
            $public_date = $request->input('public_date');
            $public_date = $public_date ? date('Y-m-d H:i:s', strtotime($public_date)) : null;
    
            if (Auth::check()) {
                // check permission
                if ($new_auth->id_user == Auth::id()) {
                    // Cập nhật bản ghi
                    DB::table('news')
                    ->where('id', $id)
                    ->update([
                        'title' => $request->input('title'),
                        'thumbnail' => $filename,
                        'content' => $request->input('content'),
                        'banner' => $request->input('banner') == 'on' ? 1 : 0,
                        'public_date' => $public_date,
                        'updated_at' => now()
                    ]);
                    return redirect('/news/auth')->with('success', 'Đã cập nhật thành công bài viết!');
                } else {
                    return redirect()->back()->with('error', 'Không có quyền chỉnh sửa bài viết này!');
                }
            }
        }
        return redirect()->back()->with('success', 'Bạn đã cập nhật thành công!');
    }

    public function news_auth_update_en(UpdateNewRequest $request, $id)
    {
        // Kiểm tra xem có file thumbnail được tải lên không
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/post/thumbnail', $filename);
        } else {
            // Nếu không có file mới, giữ nguyên tên file cũ
            $filename = DB::table('news_tran')->where('id_news', $id)->value('thumbnail');
        }
        // Kiểm tra xem có bản ghi với ID cung cấp không
        $new_auth = DB::table('news_tran')->where('id_news', $id)->first();
        if (!$new_auth) {
            abort(404);
        } else {
            // Định dạng ngày tháng cho 'public_date'
            $public_date = $request->input('public_date');
            $public_date = $public_date ? date('Y-m-d H:i:s', strtotime($public_date)) : null;
    
            if (Auth::check()) {
                // check permission
                if ($new_auth->id_user == Auth::id()) {
                    // Cập nhật bản ghi
                    DB::table('news_tran')
                    ->where('id_news', $id)
                    ->update([
                        'title' => $request->input('title'),
                        'thumbnail' => $filename,
                        'content' => $request->input('content'),
                        'banner' => $request->input('banner') == 'on' ? 1 : 0,
                        'public_date' => $public_date,
                        'updated_at' => now()
                    ]);
                    return redirect('/news/auth')->with('success', 'Đã cập nhật thành công bài viết!');
                } else {
                    return redirect()->back()->with('error', 'Không có quyền chỉnh sửa bài viết này!');
                }
            }
        }
        return redirect()->back()->with('success', 'Bạn đã cập nhật thành công!');
    }

    public function news_auth_create_en($id, NewRequest $request) 
    {
        $user = Auth::user();
        if (!$id || !is_numeric($id)) {
            return redirect('/news/auth')->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không hợp lệ.');
        }
        $existingNews = DB::table('news')->where('id', $id)->first();
        if (!$existingNews) {
            return redirect()->back()->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không tồn tại.');
        }
        $existingTranslation = DB::table('news_tran')->where('id_news', $id)->first();
        if ($existingTranslation) {
            return redirect('/news/auth')->with('error', 'Không thể tạo bản dịch tiếng anh. Phiên bản dịch tiếng Anh đã tồn tại.');
        }

        $filename = '';
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('uploads/post/thumbnail', $filename);
            } else {
                return "Lỗi khi tải tệp lên: " . $file->getErrorMessage();
            }
        }

        $public_check = $user->role == "admin" ? 1 : 0;
        $banner = $request->input('banner', 0);

        try {
            DB::beginTransaction();
            $news = [
                'id_user' => $user->id,
                'id_news' => $id,
                'title' => $request->title,
                'thumbnail' => $filename,
                'banner' => $banner,
                'content' => $request->content,
                'public_date' => $request->public_date,
                'public_check' => $public_check,
            ];
            DB::table('news_tran')->insertGetId($news);
            DB::commit();
            return redirect('/news/auth')->with('success', 'Thêm tin tức tiếng Anh thành công.!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm tin tức!');
        }
    }

    public function banner($id)
    {
        DB::table('news')->where('id', $id)->update([
            'banner' => 1,
        ]);
        return redirect()->back();
    }

    public function news_delete_mutil_auth(Request $request)
    {
        $ids = $request->check_item;
        if (is_array($ids) && count($ids) > 0) {
            $existingPosts = DB::table('news')->whereIn('id', $ids)->get();
            $existingIds = $existingPosts->pluck('id')->toArray();
            $nonExistentIds = array_diff($ids, $existingIds);
            if (!empty($nonExistentIds)) {
                return redirect()->back()->withErrors([
                    'error_selected' => 'Có ID không tồn tại: ' . implode(', ', $nonExistentIds)
                ]);
            }
            $idsToDelete = array_diff($ids, $nonExistentIds);
            DB::table('news')->whereIn('id', $idsToDelete)->update([
                'deleted_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Các bài viết đã được xóa thành công.');
        } else {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn xóa'
            ]);
        }
    }

    /* ------------------------------------ 
    Calendar 
    ---------------------------------------*/
    public function calendar()
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        // check locale
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (!in_array($locale, $allowedLocales)) {
            $postsByDate = null;
        } else {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';

            // Lấy số bài viết public_date theo ngày bằng Query Builder
            $postsByDate = DB::table($tableName)
                ->select(DB::raw('DATE(public_date) as date'), DB::raw('COUNT(*) as count'))
                ->whereNull('deleted_at')
                ->whereDate('public_date', '<=', Carbon::now())
                ->where('public_check', '1')
                ->groupBy(DB::raw('DATE(public_date)'))
                ->get();
        }
        return view('news.calendar', [
            'postsByDate' => $postsByDate,
            'infos' => $infos,
            'locale' => $locale,
        ]);
    }

    public function calendar_show($formattedDate)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        // check locale
        $allowedLocales = ['vi', 'en'];
        $locale = in_array(session('locale'), $allowedLocales) ? session('locale') : config('app.locale');
        if (!in_array($locale, $allowedLocales)) {
            $news_date = null;
            $countPost = 0;
        } else {
            $tableName = ($locale === 'en') ? 'news_tran' : 'news';

            $news_date = DB::table($tableName)
                ->join('users', 'users.id', '=', $tableName . '.id_user')
                ->select($tableName . '.*', 'users.name', 'users.role')
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '1')
                ->whereDate('public_date', $formattedDate) // Lọc theo ngày public_date
                ->whereDate('public_date', '<=', Carbon::now())
                ->latest('created_at')
                ->paginate(3);
            $countPost = DB::table($tableName)
                ->whereNull($tableName . '.deleted_at')
                ->where('public_check', '1')
                ->whereDate('public_date', $formattedDate) // Lọc theo ngày public_date
                ->whereDate('public_date', '<=', Carbon::now())
                ->count();
        }
        return view('news.calendar-show', [
            'news_date' => $news_date,
            'countPost' => $countPost,
            'formattedDate' => $formattedDate,
            'locale' => $locale,
            'infos' => $infos,
        ]);
    }
}
