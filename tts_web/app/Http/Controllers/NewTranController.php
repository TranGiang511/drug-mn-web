<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NewRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class NewTranController extends Controller
{
    public function create_en($id_news)
    {
        $infos = null;
        $infos = DB::table('info')
            ->whereNull('deleted_at')
            ->get();

        if (!$id_news || !is_numeric($id_news)) {
            return redirect('/news')->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không hợp lệ.');
        }
        $existingNews = DB::table('news')->where('id', $id_news)->first();
        if (!$existingNews) {
            return redirect('/news')->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không tồn tại.');
        }
        $existingTranslation = DB::table('news_tran')->where('id_news', $id_news)->first();
        if ($existingTranslation) {
            return redirect('/news')->with('error', 'Không thể tạo bản dịch tiếng anh. Phiên bản dịch tiếng Anh đã tồn tại.');
        }

        return view('news_tran.create', [
            'id_news' => $id_news,
            'infos' => $infos,
        ]);
    }

    public function store_en($id_news, NewRequest $request)
    {
        $user = Auth::user();

        if (!$id_news || !is_numeric($id_news)) {
            return redirect('/news')->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không hợp lệ.');
        }
        $existingNews = DB::table('news')->where('id', $id_news)->first();
        if (!$existingNews) {
            return redirect()->back()->with('error', 'Không thể tạo bản dịch tiếng anh. ID tin tức không tồn tại.');
        }
        $existingTranslation = DB::table('news_tran')->where('id_news', $id_news)->first();
        if ($existingTranslation) {
            return redirect('/news')->with('error', 'Không thể tạo bản dịch tiếng anh. Phiên bản dịch tiếng Anh đã tồn tại.');
        }

        if (!$request->filled('lang') || !in_array($request->lang, ['en'])) {
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

        $public_check = $user->role === "admin" ? 1 : 0;
        $banner = $request->input('banner', 0);

        try {
            DB::beginTransaction();
            $news = [
                'id_user' => $user->id,
                'id_news' => $id_news,
                'title' => $request->title,
                'thumbnail' => $filename,
                'banner' => $banner,
                'content' => $request->content,
                'public_date' => $request->public_date,
                'public_check' => $public_check,
            ];
            $language = $request->input('lang');
            if ($language === 'en') {
                DB::table('news_tran')->insertGetId($news);
            }
            DB::commit();
            return redirect('/news')->with('success', 'Thêm tin tức thành công.!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm tin tức!');
        }
    }
}