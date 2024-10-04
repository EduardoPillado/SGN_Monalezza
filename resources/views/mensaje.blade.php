@if (Session::has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            text: '{{ Session::get('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if (Session::has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            text: '{{ Session::get('error') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

@if (Session::has('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            text: '{{ Session::get('warning') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (Session::has('info'))
    <script>
        Swal.fire({
            icon: 'info',
            text: '{{ Session::get('info') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (Session::has('question'))
    <script>
        Swal.fire({
            icon: 'question',
            text: '{{ Session::get('question') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif