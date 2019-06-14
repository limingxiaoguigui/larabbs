<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{-- CSRF_TOKEN --}}
  <meta  name="csrf_token" content="{{csrf_token()}}">
  <title>@yield('title','Larabbs')--Laravel入门项目</title>
  <meta name="description" content="@yield('description','Larabbs入门')"/>
  {{-- 样式 --}}
  <link href="{{mix('css/app.css')}}" rel="stylesheet">
  @yield('styles')
</head>
<body>
<div id="app"  class="{{route_class()}}-page">
    @include('layouts._header')
    <div class="container">
      @include('shared._messages')
      @yield('content')
    </div>
    @include('layouts._footer')
</div>
  @if (app()->isLocal())
    @include('sudosu::user-selector')
  @endif
{{-- scripts --}}
<script  src="{{mix('js/app.js')}}"></script>
@yield('scripts')
</body>
</html>
