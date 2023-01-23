<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>larave crud</title>
 
</head>
<body>
    <div id="app">
        <main class="py-4">
           <example-component></example-component>
           <post-component></post-component>
        </main>
    </div>
    @vite('resources/js/app.js')
</body>
</html>