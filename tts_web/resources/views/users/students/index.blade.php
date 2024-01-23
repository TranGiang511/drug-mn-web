@extends('layouts.admin.app')

@section('title')
User
@endsection

@section('content')
<article class="content responsive-tables-page">
    <div class="student">
        <div class="card">
            <h1 class="text-IBM">Danh sách học viên</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="card-title-block" id="create">
                                <!-- show notification -->
                                @if(session('success'))
                                    <div class="alert alert-success" id="error-message">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger" id="error-message">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->has('error_selected'))
                                    <div class="alert alert-danger" id="error-message">
                                        <strong>Error:</strong> {{ $errors->first('error_selected') }}
                                    </div>
                                @endif

                                @if ($errors->has('import_file'))
                                    <div class="alert alert-danger" id="error-message">
                                        <strong>Error:</strong> {{ $errors->first('import_file') }}
                                    </div>
                                @endif

                                @if(session('failureRows'))
                                    <div class="alert alert-danger" id="failure-message">
                                        <h5>Errors: {{ session('failureMessage') }}</h5>
                                        <ul>
                                            @foreach (session('failureRows') as $index => $failure)
                                                <div class="failure-row" id="failure-row-{{ $index }}">
                                                    <li>Error in Row {{ $index + 1 }}:</li>
                                                    <ul>
                                                        @foreach ($failure['errors'] as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(session('successRows'))
                                    <div class="alert alert-success" id="success-message">
                                        <h5>Success: {{ session('successMessage') }}</h5>
                                        <ul>
                                            @foreach (session('successRows') as $index => $row)
                                                <div class="success-row" id="success-row-{{ $index }}">
                                                    <li>Row {{ $index + 1 }} imported successfully.</li>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Create -->
                                <a class="d-left" href="{{ url('/users/student/create') }}" style="display: block;">
                                    <img src="{{ asset('assets/img/icon-plus.png') }}" class="mw-45" style="vertical-align: middle">
                                    <span class="fz-24" style="vertical-align: middle">Thêm học viên</span>
                                </a>

                                <!-- Search -->
                                <form method="GET" class="right">
                                    <div class="input-container">
                                        <input type="text" class="search"
                                            name="search" id="search"
                                            placeholder="Search by name, phone, email"
                                            value="<?php if (isset($_GET['search'])) { echo $_GET['search'];} ?>">
                                        <button type="submit" id="btn_search" name="btn_search" hidden></button>
                                        <div class="underline"></div>
                                    </div>
                                </form>
                                <!-- Delete Mutil -->
                                <a href="javascript:void(0)"
                                    onclick="if (confirm('Bạn có chắc muốn xóa người dùng đã chọn không?')) document.getElementById('user-delete-multi').submit()"
                                    class="btn btn-danger"
                                    style="display: inline-block; margin-top: 10px;">
                                    Delete Selected
                                </a>

                                <!-- Export Excel -->
                                <form action="{{ route('export_users') }}" method="POST" id="export_form" style="display:inline-block; vertical-align: bottom;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" id="btn_export_user">Export Users</button>
                                    <input type="text" name="search_excel" id="search-field-excel" value="{{ request('search') }}" hidden>
                                </form>
                                
                                <!-- Import Excel -->
                                <form action="{{ route('import_users') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="import_file">
                                    <button type="submit" class="btn btn-primary">Import Users</button>
                                </form>

                                <!-- Select number on page -->
                                <form method="GET" class="right">
                                    <div style="display: inline-block;  vertical-align: top;">
                                        <select name="results_per_page" class="form-select" id="results-per-page" aria-label="Số lượng kết quả trên mỗi trang">
                                            <option value="10">Select the number of results in the page</option>
                                            <option value="10" {{ Request::get('results_per_page') == 10 ? 'selected' : '' }}>10</option>
                                            <option value="20" {{ Request::get('results_per_page') == 20 ? 'selected' : '' }}>20</option>
                                            <option value="30" {{ Request::get('results_per_page') == 30 ? 'selected' : '' }}>30</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <section class="example">
                                <div class="table-responsive">
                                    <form method="POST" id="user-delete-multi"
                                        action="{{ url('/usersDeleteMutil') }}">
                                        @csrf
                                        @method('DELETE')
                                        <table
                                            class="table table-bordered table-hover">
                                            <thead>
                                                <tr class="table-primary">
                                                    <!-- Check all -->
                                                    <th class="w5">
                                                        <input type="checkbox"
                                                            name="check_all"
                                                            id="check_all">
                                                    </th>
                                                    <th class="w5">ID</th>
                                                    <th class="w20">Họ Tên</th>
                                                    <th class="w20">Email</th>
                                                    <th class="w15">SĐT</th>
                                                    <th class="w15">Chức Năng</th>
                                                </tr>
                                            </thead>

                                            @foreach($students as $student)
                                            <tr>
                                                <!-- Check selected -->
                                                <td class="w5">
                                                    <input type="checkbox"
                                                        name="check_item[{{$student->id}}]"
                                                        value="{{$student->id}}"
                                                        id="check_item">
                                                </td>
                                                <td class="w5">{{ $student->id
                                                    }}</td>
                                                <td class="w20">{{
                                                    $student->name }}</td>
                                                <td class="w20">{{
                                                    $student->email }}</td>
                                                <td class="w15">{{
                                                    $student->phone }}</td>
                                                <td class="w15">
                                                    <a
                                                        href='{{ url("/users/student/{$student->id}") }}'>
                                                        <i class="fa fa-eye icon-view"></i>
                                                    </a>
                                                    <a
                                                        href='{{ url("/users/student/{$student->id}/edit") }}'>
                                                        <i class="fa fa-pencil icon-edit"></i>
                                                    </a>
                                                    <a href="{{ url("/users/student/delete/$student->id") }}"
                                                        onclick="return confirm('Bạn có chắc muốn xóa không?');">
                                                        <i class="fa fa-trash-o icon-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        {{ $students->links() }}
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Get the selected item -->
<script>
    const checkAll = document.getElementById("check_all");
    const items = document.querySelectorAll("input[type='checkbox']");

    checkAll.addEventListener("change", function () {
        items.forEach(item => {
            item.checked = checkAll.checked;
        });
    });
</script>

<!-- Get the quantity value for pagination -->
<script>
// Get the quantity value for pagination
document.getElementById('results-per-page').onchange = function () {
    var currentUrl = new URL(window.location.href);
    var searchParams = new URLSearchParams(currentUrl.search);

    searchParams.set('results_per_page', this.value);
    currentUrl.search = searchParams.toString();

    window.location.href = currentUrl.toString();
};
</script>

<!-- Get the search value to export -->
<script>
    document.getElementById('btn_search').addEventListener('click', function() {
        var searchValue = document.getElementById('search').value;
        document.getElementById('search-field-excel').value = searchValue;
        document.getElementById('export_form').submit();
    });
</script>

<!-- Get checkbox send form export form -->
<script>
    document.getElementById("btn_export_user").addEventListener("click", function() {
        var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="check_item"]:checked');
        
        // Checkbox được chọn, thêm các giá trị vào form
        var form = document.getElementById('export_form');
        var inputsInForm = form.querySelectorAll('input[type="hidden"][name^="check_item"]');
        inputsInForm.forEach(input => {
            // Xóa các input ẩn cũ
            input.remove(); 
        });
        selectedCheckboxes.forEach(selectedCheckbox => {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = selectedCheckbox.name;
            hiddenInput.value = selectedCheckbox.value;
            form.appendChild(hiddenInput);
        });
    });
</script>

<script>
    // Hiển thị và ẩn thông báo lỗi và thành công từng dòng lần lượt
    function showHideMessages() {
        let failureRows = document.querySelectorAll('.failure-row');
        let successRows = document.querySelectorAll('.success-row');
        let currentIndex = 0;

        const interval = setInterval(function() {
            if (currentIndex < failureRows.length) {
                failureRows[currentIndex].style.display = 'none'; // Ẩn thông báo lỗi dòng hiện tại
                currentIndex++;
                if (currentIndex < failureRows.length) {
                    failureRows[currentIndex].style.display = 'block'; // Hiển thị thông báo lỗi dòng tiếp theo
                }
            } else if (currentIndex < failureRows.length + successRows.length) {
                successRows[currentIndex - failureRows.length].style.display = 'none'; // Ẩn thông báo thành công dòng hiện tại
                currentIndex++;
                if (currentIndex < failureRows.length + successRows.length) {
                    successRows[currentIndex - failureRows.length].style.display = 'block'; // Hiển thị thông báo thành công dòng tiếp theo
                }
            } else {
                clearInterval(interval); // Dừng khi đã ẩn hết thông báo
                
                // Ẩn thông báo lỗi sau 1 giây
                if (document.getElementById('error-message')) {
                    setTimeout(function() {
                        document.getElementById('error-message').style.display = 'none';
                    }, 1000);
                }

                if (document.getElementById('failure-message')) {
                    setTimeout(function() {
                        document.getElementById('failure-message').style.display = 'none';
                    }, 1000);
                }

                if (document.getElementById('success-message')) {
                    setTimeout(function() {
                        document.getElementById('success-message').style.display = 'none';
                    }, 1000);
                }
            }
        }, 500); // 0.5 giây cho mỗi thông báo
    }
    
    // Gọi hàm để bắt đầu hiển thị và ẩn thông báo
    showHideMessages();
</script>


@endsection