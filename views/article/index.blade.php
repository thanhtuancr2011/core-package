@extends('core::master')
@section('title')
    {{($type != 'page')  ? 'Articles' : 'Pages'}}
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="ArticleController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"> {{($type != 'page')  ? 'Articles Manager' : 'Pages Manager'}} </h3>
                    <h3 class="c-m">
                        <a data-toggle="modal" href="javascript:void(0)" ng-click="getModalArticle()" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> {{($type != 'page')  ? 'Add Article' : 'Add Page'}}
                        </a>
                    </h3>
                </div>
                <table class="table table-hover fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="article in $data">
                            <td data-title="'Title'" filter="{ 'title': 'text' }" sortable="'title'" >
                                @{{article.title}}
                            </td>
                            <td data-title="'Author'" filter="{ 'author': 'text' }" sortable="'author'" >
                                <img class="img-circle" ng-src="@{{article.authAvatar}}" alt="" height="40">
                                @{{article.author}}
                            </td>
                            <td class="text-center" data-title="'Description'" filter="{ 'description': 'text' }" sortable="'description'">
                                @{{article.description}}
                            </td>
                            <td class="text-center" data-title="'Status'" sortable="'status'">
                                @{{article.status}}
                            </td>
                            <td class="text-center" data-title="''">
                                <a ng-click="getModalArticle(article.id)" class="action-icon">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a ng-click="removeArticle(article.id, 'sm')" class="action-icon">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.articles = {!! json_encode($items) !!}
        window.type = {!! json_encode($type) !!}
    </script>
    {!! Html::script('core/components/article/ArticleService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/article/ArticleController.js?v='.getVersionScript())!!}
@endsection