@extends('admin.main')

@section('title', 'Konfigurasi Virtual Tour')

@section('content')
@if ($message = Session::get('success'))
    <div class="alert-dismiss">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span class="fa fa-times"></span>
            </button>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 mt-sm-30 mt-xs-30">
        <div class="card">
            <div class="card-body">
                <!-- Tab -->
                <div class="d-flex justify-content-center">
                    <div class="trd-history-tabs">
                        <ul class="nav" role="tablist" id="TabMenu">
                            <li><a class="active" data-toggle="tab" href="#scene" role="tab">Scene</a></li>
                            <li><a data-toggle="tab" href="#hotspot" role="tab">Hotspot</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="trad-history mt-4" >
                    <div class="tab-content" id="myTabContent" >
                        <!-- Scene Tab -->
                        <div class="tab-pane fade show active" id="scene" role="tabpanel">
                            @include('admin.dataScene')
                        </div>

                        <!-- Hotspot Tab -->
                        <div class="tab-pane fade" id="hotspot" role="tabpanel">
                            @include('admin.dataHotspot')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        function previewImage() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image-upload").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("image-preview").src = oFREvent.target.result;
            };
        };
    </script>

    <script>
        $(document).ready(function() {
            $('.configTable').DataTable({
                pageLength : 5,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Semua']]
            });
        } );
    </script>

    <script>
        (function ($, DataTable) {
            $.extend(true, DataTable.defaults, {
                pageLength : 5,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Semua']],
                language: {
                    url : '//cdn.datatables.net/plug-ins/1.10.10/i18n/Indonesian.json'
                }
            });
        })(jQuery, jQuery.fn.dataTable);
    </script>

    <script>
        $(document).ready(function()  {
            var table = $('.sceneTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dataScene') }}",
                columns: [
                    {data: 'id'},
                    {data: 'title'},
                    {data: 'image',
                        "render": function(data, type, full, meta){
                            return "<img style='height:70px;' src= 'img/uploads/" + data + "' />";
                        },
                        orderable: false,
                        searchable: false
                    },
                    {data: 'status', 
                        name: 'status',
                        render: function ( data, type, row ) {
                            if ( type === 'display' ) {
                                return '<input type="checkbox" name="check">';
                            }
                            return data;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {data: 'action', 
                        name: 'action',
                        orderable: false, 
                        searchable: false
                    }
                ]
            });
        });

        $('.sceneTable').on( 'change', 'input.editor-status', function () {
            editor
                .edit( $(this).closest('tr'), false, {
                        submit: 'changed'
                    } )
                .set( 'active', $(this).prop( 'checked' ) ? 1 : 0 )
                .submit();
        });
    </script>

    <script>
        $(function () {
            var table = $('.hotspotTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dataHotspot') }}",
                columns: [
                    {data: 'id', name: 'hotspots.id'},
                    {data: 'sourceSceneName', name: 'sc1.title'},
                    {data: 'targetSceneName', name: 'sc2.title'},
                    {data: 'type', name: 'hotspots.type'},
                    {data: 'info', name: 'hotspots.info'},
                    {data: 'action', 
                        name: 'action',
                        orderable: false, 
                        searchable: false
                    }
                ]
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("input:checkbox").change(function(){
                var getId = $(this).attr("id");
                if ($('input[type=checkbox]:checked').length > 1) {
                    $(this).prop('checked', false)
                    alert('Scene Utama Hanya Diperbolehkan 1')
                }else{
                    $(this).find('input[name="check"]:not(:checked)').prop('checked', true).val(0);
                    $("#status"+getId).submit();
                }
            });
        });
    </script>

    <script type="text/javascript">
        @if (count($errors) > 0)
            document.querySelectorAll('#addScene', '#addHotspot').modal('show');
        @endif
    </script>
@endpush