<!DOCTYPE html>
<html>

<head>
    <title>HPK URL Shortner</title>
    @vite('resources/js/app.js')
</head>

<body>
    <div id="app"></div>
    <script>
        window.config = {
            base_url: "{{ route('app', '/') }}"
        }
    </script>
</body>

</html>
