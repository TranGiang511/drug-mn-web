<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    //list of users with the user role
    public function index(Request $request)
    {
        $results_per_page = $request->results_per_page ?? 5;
        if (isset($results_per_page) && $results_per_page !== 0) {
            // Search by name, phone, email
            if ($request->has('search')) {
                $search = $request->input('search');
                $data_search = DB::table('users')
                    ->where('role', 'user')
                    ->whereNull('deleted_at')
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('phone', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    })
                    ->paginate($results_per_page)
                    ->withQueryString();

                return view('users.students.index', [
                    'students' => $data_search->appends(['search' => $search]),
                ]);
            }

            $data = DB::table('users')
                ->select('id', 'name', 'email', 'phone')
                ->where('role', 'user')
                ->whereNull('deleted_at')
                ->paginate($results_per_page);

            return view('users.students.index', [
                'students' => $data,
            ]);
        }
    }

    //show detail a user by id
    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $auth_user = Auth::user();

        if ($auth_user) {
            if ($user != null && $user->deleted_at == null && ($auth_user->id == $id || ($auth_user->role == 'admin' && $user->role != 'admin'))) {
                // Trường hợp người dùng là chính họ hoặc là tài khoản đăng nhập là admin (ko xem được admin khác, xem được của chính mình và user)
                $data = DB::table('users')
                    ->select('*')
                    ->where('id', $id)
                    ->first();
                return view('users.students.show', [
                    'student' => $data,
                ]);
            }
        }

        abort(404);
    }


    //view create user
    public function create()
    {
        return view('users.students.create');
    }

    //create a user with the user role
    public function store(UserRequest $request)
    {
        $data = DB::table('users')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
            'role' => 'user'
        ]);
        if ($data) {
            return redirect('/users/student')->with('success', 'Tài khoản được tạo thành công.');
        }
    }

    //view edit user
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $auth_user = Auth::user();

        if ($auth_user) {
            // Trường hợp người dùng là chính họ hoặc là tài khoản đăng nhập là admin (ko xem được admin khác, xem được của chính mình và user)
            if ($user != null && $user->deleted_at == null && ($auth_user->id == $id || ($auth_user->role == 'admin' && $user->role != 'admin'))) {
                $data = DB::table('users')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

                return view('users.students.edit', [
                    'student' => $data
                ]);
            }
        }

        abort(404);
    }

    //update a user by id
    public function update(UpdateUserRequest $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $auth_user = Auth::user();

        if ($auth_user) {
            // Trường hợp người dùng là chính họ hoặc là tài khoản đăng nhập là admin (ko xem được admin khác, xem được của chính mình và user)
            if ($user != null && ($auth_user->id == $id || ($auth_user->role == 'admin' && $user->role != 'admin'))) {
                $data = $request->input();
                $new_password = $request->new_password;
                $confirm_password = $request->confirm_password;

                if ($new_password === $confirm_password) {
                    DB::table('users')
                        ->where('id', $data['id'])
                        ->whereNull('deleted_at')
                        ->update([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'],
                            'password' => bcrypt($new_password),
                            'reset_password_status' => 1,
                        ]);
                    // Remove the login session
                    DB::table('login_session')
                        ->where('id_user', $id)
                        ->delete();

                    session()->flash('success', 'Tài khoản đã được cập nhật thành công. Vui lòng đăng nhập lại.');
                    return redirect('/users/student')->with('success', 'Tài khoản được cập nhật thành công.');
                } else {
                    return redirect("/users/student/$id/edit")
                        ->withErrors(['invalid' => 'Kiểm tra lại xác thực mật khẩu']);
                }
            }
        } else {
            abort(404);
        }
    }

    //delete a user by id
    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $auth_user = Auth::user();

        if ($user != null && $user->role != 'admin' && $auth_user->role == 'admin') {
            $data = DB::table('users')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->where('role', 'user')
                ->update([
                    'deleted_at' => now(),
                ]);

            if ($data) {
                return redirect('/users/student')->with('success', 'Tài khoản được xóa thành công.');
            } else {
                return redirect('/users/student')->with('error', 'Không thể tìm thấy tài khoản để xóa.');
            }
        } else {
            abort(404);
        }
    }

    //view seft show user
    public function self_show()
    {
        $user = Auth::user();
        return view('users.self_show', [
            'user' => $user
        ]);
    }

    //view seft edit user
    public function self_edit($id)
    {
        $user = Auth::user();
        if ($id = $user->id) {
            return view('users.self_edit', [
                'user' => $user
            ]);
        } else {
            return redirect("/users/self_edit/$id")->withErrors(['invalid' => 'Không có quyền sửa']);
        }
    }

    //update seft user
    public function self_update(UpdateUserRequest $request, $id)
    {
        $user = Auth::user();
        if ($id = $user->id) {
            $new_password = $request->input('new_password');
            $confirm_password = $request->input('confirm_password');

            if ($new_password == $confirm_password) {
                DB::table('users')->where('id', $user->id)->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => bcrypt($request->input('new_password')),
                ]);

                // Remove the login session
                DB::table('login_session')
                    ->where('id_user', $id)
                    ->delete();

                Auth::logout();
                return redirect('/login')->with('success', 'Cập nhật thông tin thành công. Hãy đăng nhập lại với thông tin đã cập nhật mới!');
            } else
                return redirect("/users/self_edit/$id")->withErrors(['invalid' => 'Kiểm tra lại xác thực mật khẩu']);
        } else {
            return redirect("/users/self_edit/$id")->withErrors(['invalid' => 'Không có quyền sửa']);
        }
    }

    //delete mutil user
    function delete_mutil(Request $request)
    {
        $auth_user = Auth::user();
        $ids = $request->check_item;

        if (is_array($ids) && $auth_user->role == 'admin') {
            $users = DB::table("users")
                ->whereIn('id', $ids)
                ->whereNull('deleted_at')
                ->where('role', 'user')
                ->update([
                        'deleted_at' => now(),
                    ]);
            return redirect('/users/student');
        } else {
            return redirect()->back()->withErrors([
                'error_selected' => 'Chưa chọn dữ liệu để xóa. Hãy tích vào các ô muốn xóa!'
            ]);
        }
    }

    //export excel search, all, selected
    public function export(Request $request)
    {
        $search = $request->input('search_excel');
        $selectedUsers = $request->check_item;
        $fileName = 'users.xlsx';

        $query = DB::table('users')
            ->select('users.*')
            ->where('role', 'user')
            ->whereNull('deleted_at')
            ->orderBy('id');

        if (!empty($selectedUsers)) {
            $query->whereIn('id', $selectedUsers);
            $fileName = 'users_selected.xlsx';
        }

        if ($search != '') {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });

            // If both search and selected users, update the file name
            if (!empty($selectedUsers)) {
                $fileName = 'users_search_' . $search . '_selected' . '.xlsx';
            } else {
                $fileName = 'users_search_' . $search . '.xlsx';
            }
        }

        return Excel::download(new UsersExport($query, $search), $fileName);
    }

    //import excel
    public function import(Request $request)
    {
        // Check if the 'import_file' input exists
        if (!$request->hasFile('import_file')) {
            return redirect()->back()->withErrors([
                'import_file' => 'Hãy chọn một file excel để import!'
            ]);
        }

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Get the file and start the import
        $file = $request->file('import_file');
        $import = new UsersImport;
        $rows = Excel::toArray($import, $file);

        $successRows = [];
        $failureRows = [];

        foreach ($rows[0] as $index => $row) {
            $validator = Validator::make($row, $import->rules(), $import->customValidationMessages());

            if ($validator->fails()) {
                // Row has validation errors
                $failureRows[$index] = [
                    'row' => $row,
                    'errors' => $validator->errors()->all(),
                ];
            } else {
                // Row is valid, you can process and import it
                $import->model($row);
                $successRows[] = $row;
            }
        }

        $successMessage = count($successRows) . ' rows imported successfully.';
        $failureMessage = count($failureRows) . ' rows had errors.';

        // Store success and failure data in the session
        Session::flash('successMessage', $successMessage);
        Session::flash('failureMessage', $failureMessage);
        Session::flash('successRows', $successRows);
        Session::flash('failureRows', $failureRows);

        return redirect()->back();
    }
}