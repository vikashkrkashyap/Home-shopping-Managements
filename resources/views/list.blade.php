@extends('main')
@section('page-body')
<!-- For error message-->
    @if(count($errors))
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" style="width:35%;margin: 0 0 1% 28%;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>{{ $error.'select Again'}}</strong></div>
        @endforeach
    @endif
    <div class="item-container" style="color:white; margin:5% 0 0 5%; width:50%;float: left" id="item-list">
        <h1 style="margin-left:10%;">Your shopping items list</h1>

        <!--checking if item is present then shown otherwie message shown  -->
        @if(count($items)>0)

                <!-- Table for item list-->
            <table class="table table-bordered" style="width: 80%;margin:10%;background:slateblue">
            <tr class="menu" align="center">
                <td colspan="4"><a href="#" id ="purchased-link">Items</a></td>

            <tr>
            <tr style="color:black">
                <th>Select</th>
                <th>item</th>
                <th>item name</th>
                <th>Date</th>

            <tr class="">
                    <!-- showing th value of list table from the database-->
            <?php $i=1 ?>
            @foreach($items as $item)
                <tr>
                    <td>
                        <input type="checkbox" name="{{$item->item_id}}" item-name="{{$item->item_name}}" class="checkbox1"></td>
                    <td>{{$i++}}</td>
                    <td>{{$item->item_name}}</td>
                    <td>{{$item->date}}
                </tr>
            @endforeach
        </table> <!--item list table ended -->

        <!-- Showing the message if no list created-->
        @else
        <h3 align="center">Your shopping items list is empty Add some!</h3>
          @endif
        </div>
       <!--Doing the same thing for the purchased item -->
        <div class="item-container" style="color:white; margin: 5% 0 0 5%; width:50%;float: left" id="purchased-list" hidden>
            <h1  style="margin-left:10%">Your purchased items list</h1>
            @if(count($purchased_items))
                <table class="table table-bordered" style="width: 80%;margin:10%;background:slateblue" >
                    <tr class="menu" align="center">
                        <td colspan="4"><a href="" id ="item-link">Item list</a></td >

                    <tr>
                    <tr style="color:black">
                        <th>Item</th>
                        <th>item name</th>

                        <th>Store</th>
                        <th>Date</th>
                    <tr class="menu">
                    <?php $i=1 ?>
                    @foreach($purchased_items as $item)
                        <tr>

                            <td>{{$i++}}</td>
                            <td>{{$item->item_name}}</td>

                            <td>{{$item->store}}</td>
                            <td>{{$item->date}}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <h3 align="center">Your purchase items list is empty purchase some items!</h3>
            @endif
        </div>
<!--create the new items-->



<div id ="create list" style="width:370px;float:left; margin:50px 0 0 100px;">
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
                <input type="text" class="form-control" placeholder="Add your items!" name="item_name" value="{{old('item_name')}}" style="margin-left:5px">
                                <span class="input-group-btn">
                                    <input type="submit" class="btn btn-success" type="button" value ="Add items!">
                                </span>
            </div><!-- /input-group -->
        </form>


<!--Div for trending item -->
    <div class="jumbotron" style="width:350px;margin:7% 0 0 2%;float:left;background:cornflowerblue;color:white">
        <h3 align="center">Trending shops</h3><hr>
        @if(count($trends))
            <table class="table table-bordered" style="width: 80%;margin:5% 5%;background:black">
                <tr>
                    <th>Store name</th>
                    <th>Total Revenue</th>
                    <th>Item sold</th>

                @foreach($trends as $trend)
                    <tr>
                        <td>{{$trend->store}}</td>
                        <td>{{$trend->price}}</td>
                        <td>{{$trend->visit}}</td>
                    </tr>
                @endforeach
            </table>

        @else
            <strong>You have not bought anyitems.please mark as bought to see the trends</strong>
        @endif
    </div>


<!--Form for the saving the iteam as purchased -->
     <form class="shopped" method="post" action="{{url('post-data')}}" id="purchase-data" hidden><br>
         {!! csrf_field()!!}
         <a class="boxclose" id = "boxclose"></a>
         <h4 align="center">Add some information to mark as shopped</h4>
         <label for ="item">Items</label>
         <input type="text" class ="item" value=""  disabled><br>
         <label for ="price">Price</label>
         <input type="text" name="price" placeholder="item price"><br>
         <label for ="store_name">Store</label>
         <input type="text" name="store_name" placeholder="store-name"><br>
         <input type="hidden" name ="item-checkbox" value="">
         <input type="submit" value="Add as purchased" class="btn btn-info" style="margin:20px 0 20px 60px"><br>
    </form>
@endsection
@section('script')
        <!--staring of javascript -->
    <script>

$(function () {
    //for the showing the items and purchased items

    $('#purchased-link').click(function(e){
        e.preventDefault();
        $('#item-list').hide();
        $('#purchased-list').show();
    });

    //getting the value and id from the respective checkbox
    $('.checkbox1').on('change',function(e){
        e.preventDefault();

        var a= this.name;
        $('input[name="item-checkbox"]').val(a);

        var dataId = $(this).attr('item-name');
                $('.item').val(dataId);
               $('.shopped').show();

    });

    //script for closing the purchased as tab from escape key
        $( document ).on( 'keydown', function ( e ) {
            if (e.keyCode == 27) {
                $('.shopped').hide();
                $('.checkbox1').attr('checked', false);
            }
        });

        $('#boxclose').click(function(){
            $('.shopped').hide();
            $('.checkbox1').attr('checked', false);
            });
    //script for working of close icon of alert message
        $(".alert").delay(3000).slideUp(200, function() {
            $(this).alert('close');
        });


});
    </script>
@endsection