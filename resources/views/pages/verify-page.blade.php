@extends('layout.app')
@section('content')
    @include('component.MenuBar')
    @include('component.Verify')
    @include('component.TopBrands')
    @include('component.Footer')
    <script>
        (async () => {
            $(".preloader").delay(90).fadeOut(10).addClass('loaded');
        })()
    </script>
@endsection

