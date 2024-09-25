@extends('vendor.frontend.layout.main')

@section('title', 'Posts')

@section('style-libraries')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
@stop

@section('styles')
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #66b0ff;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination a.active {
            background-color: #66b0ff;
            color: white;
            border: 1px solid #66b0ff;
        }
    </style>
@stop

@section('content')
    <div class="main-content">
        <div class="top-page">
            <h1>Danh Sách Bài Viết</h1>

            <label for="perPage">Số lượng phần tử trên một trang:</label>

            <select id="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="">All</option>
            </select>

            <br/>
            <br/>

            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <ul class="pagination">
            </ul>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        $(function () {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var currentPage = 1;
            var perPage = $('#perPage').val();

            function fetchData(page, perPage) {
                $.ajax({
                    url: `http://127.0.0.1:81/api/post?page=${page}&perPage=${perPage}`,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (data) {
                        $('.table tbody').empty();
                        let dataResponse = perPage ? data.data : data;
                        dataResponse.forEach(function (post) {
                            $('.table tbody').append(`
                                <tr>
                                    <td><a href="detail/${post.id}">${post.id}</a></td>
                                    <td><img src="${post.image}" alt="${post.title}" width="50"></td>
                                    <td><a href="detail/${post.id}">${post.title}</a></td>
                                </tr>
                            `);
                        });

                        $('.pagination').empty();
                        for (let i = 1; i <= data.last_page; i++) {
                            let activeClass = (i === currentPage) ? 'active' : '';
                            $('.pagination').append(`
                                <li><a href="#" class="page-link ${activeClass}" data-page="${i}">${i}</a></li>
                            `);
                        }
                    },

                    error: function (xhr, status, error) {
                        console.error('Error: ' + error);
                    }
                });
            }

            fetchData(currentPage, perPage);

            $('#perPage').change(function () {
                perPage = $(this).val();
                currentPage = 1;
                fetchData(currentPage, perPage);
            });

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                currentPage = $(this).data('page');
                fetchData(currentPage, perPage);
            });
        });
    </script>
@stop
