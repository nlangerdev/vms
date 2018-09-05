<!doctype html>
    <html lang="en">
        <head>
            @include('Global.Scripts.css')
        </head>
    <body>
        @include('Websites.VisualGroup.Partials.Core.header')
      @yield('pagecontent')
    @include('Global.Scripts.js')
    </body>
</html>