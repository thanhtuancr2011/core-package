<div id="page-loading" style="display: block" class="overlay-loading">
    <div class="spin-box"></div>
</div>

{!! Html::script('bower_components/jquery/dist/jquery.min.js')!!}
{!! Html::script('bower_components/jquery-ui/jquery-ui.min.js')!!}  
{!! Html::script('bower_components/angular/angular.min.js')!!}  
{!! Html::script('bower_components/angular-resource/angular-resource.js')!!}
{!! Html::script('bower_components/angular-bootstrap/ui-bootstrap.min.js')!!}
{!! Html::script('bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js')!!}
{!! Html::script('bower_components/ng-table/dist/ng-table.min.js')!!}
{!! Html::script('bower_components/ngImgCrop/source/js/init.js')!!}
{!! Html::script('bower_components/ngImgCrop/source/js/ng-img-crop.js')!!}
{!! Html::script('bower_components/ngImgCrop/compile/minified/ng-img-crop.js')!!}
{!! Html::script('bower_components/angular-xeditable/dist/js/xeditable.js') !!}
{!! Html::script('bower_components/angular-sanitize/angular-sanitize.js')!!}
{!! Html::script('bower_components/jquery.maskedinput/dist/jquery.maskedinput.min.js')!!}
{!! Html::script('bower_components/ckeditor/ckeditor.js')!!}
{!! Html::script('bower_components/ckeditor/adapters/jquery.js')!!}
{!! Html::script('bower_components/mask-money/dist/jquery.maskMoney.js')!!}
{!! Html::script('bower_components/mask-money/dist/jquery.maskMoney.min.js')!!}

<!-- My Angular js File -->
{!! Html::script('core/app/app.js')!!}
{!! Html::script('core/app/config.js')!!}

<!-- jquery  select 2 -->
{!!Html::script('bower_components/select2/dist/js/select2.min.js')!!}

<!-- upload js  -->
{!! Html::script('bower_components/ng-file-upload/ng-file-upload-all.min.js')!!}
{!! Html::script('bower_components/ng-file-upload/ng-file-upload-shim.min.js')!!}

<script type="text/javascript">
    window.baseUrl = '{{URL::to("")}}';
</script>

<!-- Bootstrap Core JavaScript -->
{!!Html::script('bower_components/bootstrap/dist/js/bootstrap.min.js')!!}

<!-- Metis Menu Plugin JavaScript -->
{!!Html::script('bower_components/metisMenu/dist/metisMenu.min.js')!!}

<!-- Custom Theme JavaScript -->
{!!Html::script('core/js/sb-admin-2.js')!!}

@yield('script')



    