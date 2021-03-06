@extends('layouts.app', ['page' => __('Catalog Edit'), 'pageSlug' => 'catalog'])

@section('content')

<div class="row">
    <div class="col-md-8 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{_('Add Catalog')}}</h4>
            </div>
            <form id = "Form">
                <div class="card-body">
                    @include('alerts.success')

                    @csrf
                    <div class="form-group{{ $errors->has('form') ? ' has-danger' : '' }}">
                        <label>{{ _('Nama Catalog') }}</label>
                        <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ _('Nama Catalog') }}" autocomplete="off">
                        @include('alerts.feedback', ['field' => 'name'])
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-fill btn-primary" id="ajaxSubmit">{{ _('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-8 offset-2">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title mt-0">Catalog List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="overflow: auto;">
                        <table class="table table-hover" style="margin-bottom: 0 !important;">
                            <thead class="">
                                <tr>
                                    <th>Nama</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive" style="overflow-x: auto; max-height: 39vh !important;">
                        <table class="table table-hover">
                            <tbody id="dataContainer">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        getData();
        $('#ajaxSubmit').click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            let name = $("input[name=name]").val();
            $.ajax({
                url: "http://127.0.0.1:8000/api/catalogtype",
                method: 'post',
                data: {
                    name:name
                },
                success: function(){
                    document.getElementById("Form").reset();
                    getData();
                    $.notify({
                        icon: "tim-icons icon-bell-55",
                        message: "New Catalog added."
                    },{
                        type: type['#f6383b'],
                        timer: 5000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        }
                    });
                }
            });
        });
    });

    function deletion(id){
        if(confirm("Are you sure to delete this record ?")){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })
            $.ajax({
                url: `http://127.0.0.1:8000/api/catalogtype/`+id,
                method: 'delete',
                data: id,
                success: function(response){
                    // console.log(response.message);
                    if(response.message=='deleted'){
                        getData();
                        $.notify({
                            icon: "tim-icons icon-bell-55",
                            message: "Catalog removed."
                        },{
                            type: type['#f6383b'],
                            timer: 5000,
                            placement: {
                                from: 'top',
                                align: 'center'
                            }
                        });
                    }else{
                        $.notify({
                            icon: "tim-icons icon-bell-55",
                            message: "Failed, catalog still have product!"
                        },{
                            type: type['#f6383b'],
                            timer: 5000,
                            placement: {
                                from: 'top',
                                align: 'center'
                            }
                        });
                    }
                }
            });
        }
    }

    function getData() {
        $('#dataContainer').html("");
        $.ajax({
            url: `http://127.0.0.1:8000/api/catalogtype`,
            method: 'GET',
            success: (response) => {
                const data = JSON.parse(response);
                data.forEach(function(item){
                    HTML =
                        '<tr>' +
                        '   <td>'+ item.name +'</td>' +
                        '       <td width="50">' +
                        '           <button class="btn btn-danger" id="deletion_'+ item.id +'" onclick="deletion('+ item.id +')">' +
                        '               <i class="tim-icons icon-trash-simple"></i>' +
                        '           </button>' +
                        '       </td>' +
                        '</tr>';
                    $('#dataContainer').append(HTML);
                });
            }
        });
    }
</script>
@endsection
