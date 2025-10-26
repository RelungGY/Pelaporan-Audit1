@include('user.layouts.head')
{{-- @include('user.layouts.sidebar') --}}
@include('user.layouts.navbar')
@include($data['content'], $data)
@include('user.layouts.footer')
