@extends('layout.app')
@section('content')
    @include('component.MenuBar')
    @include('component.ByCategoryList')
    @include('component.TopBrands')
    @include('component.Footer')
    <script>
        (async () => {
            await Category();
            await ByCategory();
            $(".preloader").delay(90).fadeOut(10).addClass('loaded');

            await TopBrands();
        })()
    </script>
@endsection





