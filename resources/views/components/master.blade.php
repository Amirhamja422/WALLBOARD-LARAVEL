<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--favicon-->
    <link rel="icon" href="{{ URL::asset('images/favicon-32x32.png') }}" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <title>iHelpBD - {{ $title }}</title>

    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <x-partials.sidebar />
        <!--end sidebar wrapper -->

        <!--start header -->
        <x-partials.navbar />
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                {{ $content_area }}
            </div>
        </div>
        <!--end page wrapper -->

        <!-- start footer -->
        <x-partials.footer />
        <!-- end footer -->
    </div>
    <!--end wrapper-->

    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            setInterval(function() {
                // downloadUnseenCount();
            }, 3000);
        });

        /**
         * Download notifications count
         */
        function downloadUnseenCount() {
            $.ajax({
                url: "download-unseen-count",
                method: 'GET',
                success: function(response) {
                    if (response.status == '200') {
                        $('.download-alert-count').text(response.msg);
                    }
                },
            });
        }

        /**
         * Download notifications
         */
        function downloadNotificationsList() {
            $.ajax({
                url: "download-notifications",
                method: 'GET',
                success: function(response) {
                    if (response.status == '200') {
                        const downloadNotificatios = $('.header-notifications-list');
                        downloadNotificatios.html('');
                        response.msg.map((data) => {
                            let perseName = JSON.parse(data.name);
                            let fileName = "{{ asset('storage') }}" + '/' + perseName.file_name;
                            const downloadData = `<a class="dropdown-item" href="${fileName}" download>
                                    <div class="d-flex align-items-center">
                                        <div class="notify bg-light-primary text-primary"><i class="bi bi-download"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">${perseName.title}<span class="msg-time float-end">14 Sec
                                                    ago</span></h6>
                                            <p class="msg-info">Start: ${perseName.start} - End: ${perseName.end}</p>
                                        </div>
                                    </div>
                                </a>`;

                            downloadNotificatios.append(downloadData);
                        })
                    }
                },
            });
        }


        function insertData() {

        }

        /**
         * blank input value
         */
        function blankValue(class_id) {
            $('.' + class_id).find('select, textarea, input').val('');
        }

        /**
         * error message
         */
        function errorMsg(error) {
            $.each(error.responseJSON.errors, function(key, value) {
                $("." + key).text(value[0]);
                $("#" + key).addClass("error_box");
            });
        }

        /**
         * success message
         */
        function successMsg(success) {
            if (success.status == '200') {
                $('#dataTable').DataTable().draw(false);
                successAlert(success.msg);
            }
        }

        /**
         * remove error class
         */
        function rmvErrorClass(class_id) {
            $('.' + class_id).find('.error_txt').text('');
            $('.' + class_id).find('select, textarea, input').removeClass('error_box');
        }

        /**
         * success alert
         *
         * @param msg
         */
        function successAlert(msg) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: msg
            })
        }
    </script>
    @stack('js')
</body>

</html>
