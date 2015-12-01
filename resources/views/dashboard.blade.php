@extends('main')
@section('page-body')

    <div class="item-container" style="color:white; margin:5% 0 0 20%; width:70%">
         <h2>Welcome to shopping Management system.<a style="color:greenyellow" href="{{url('items')}}">see list</a></h2>


        <div class="well well-lg" style="margin:5% 10%; width:50%;">


            @if(count($errors))
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error}}</div>
                @endforeach
            @endif
                @if(session()->has('status'))
                    <div class="alert alert-success fade-in" style="margin-left:5%;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>{{session('status')}}</strong>
                    </div>
                @endif
                @if(session()->has('list'))
                    <div class="alert alert-danger fade-in" style="margin-left:5%;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>{{session('list')}} </strong>
                    </div>
                @endif
                    <div class="col-lg-12">
                        <form method="POST" action="{{url('store-items')}}">
                            {!! csrf_field()!!}
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Add your items!" name="item_name" value="{{old('item_name')}}">
                                <span class="input-group-btn">
                                    <input type="submit" class="btn btn-success" type="button" value ="Add items!">
                                </span>
                            </div><!-- /input-group -->
                        </form>
                    </div><br><br><!-- /.col-lg-12 -->
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(".alert").delay(4000).slideUp(200, function() {
            $(this).alert('close');
        });
    </script>
    @endsection
