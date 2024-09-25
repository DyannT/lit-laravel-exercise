@extends('vendor.frontend.layout.main')

@section('title', 'Posts')

@section('style-libraries')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
@stop

@section('styles')
    <style>
        .img-page {
            flex: 0 0 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .img-page img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }

        .description-page {
            width: 75%;
            margin-left: 3rem;
        }
    </style>

@stop

@section('content')
    <div class="main-content" data-post-id="{{$id}}">
        <div class="top-page">
            <div class="main-content">
                <div class="top-page">
                    <div style="display: flex;align-items: center;justify-content: space-between;">
                        <h1 class="title"></h1>
                        <a class="button" href="/">Back</a>
                    </div>
                    <hr />
                    <div style="display: flex">
                        <div class="img-page">

                        </div>
                        <div class="description-page">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.4.10/jquery.autocomplete.min.js"></script>

    <script>
        $(function () {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var postId = $('.main-content').data('post-id'); // Changed to use jQuery's data method

            $.ajax({
                url: 'http://127.0.0.1:81/api/post/' + postId,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (data) {
                    if (data) {
                        $('.top-page .title').text(data.title);
                        $('.img-page').html('<img src="http://127.0.0.1:81/' + data.image + '" alt="' + data.title + '">');
                        $('.description-page').html('<p>' + data.description + '</p>');
                    } else {
                        console.error('No data returned');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        });
    </script>
@stop

